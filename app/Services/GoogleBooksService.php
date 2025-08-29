<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    public function importBooks(string $query, int $maxResults = 5): array
    {
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => $query,
            'maxResults' => $maxResults,
        ]);

        if (!$response->successful()) {
            return [];
        }

        $items = $response->json()['items'] ?? [];
        $books = [];

        foreach ($items as $item) {
            $volume = $item['volumeInfo'] ?? [];
            $title = $volume['title'] ?? 'Sem título';
            $googleId = $item['id'];

            // Verifica se já existe um livro com este google_id
            $book = Book::firstWhere('google_id', $googleId);

            if (!$book) {
                // Publisher
                $publisherName = $volume['publisher'] ?? 'Desconhecido';
                $publisher = Publisher::firstOrCreate(['name' => $publisherName]);

                // Evita duplicar livros com mesmo título e publisher
                $existingBook = Book::where('name', $title)
                                    ->where('publisher_id', $publisher->id)
                                    ->first();
                if ($existingBook) {
                    $book = $existingBook; // usa o existente
                } else {
                    // Cria o livro
                    $book = Book::create([
                        'google_id'    => $googleId,
                        'isbn'         => $this->extractIsbn($volume),
                        'name'         => $title,
                        'publisher_id' => $publisher->id,
                        'bibliography' => $volume['description'] ?? null,
                        'cover_image'  => $volume['imageLinks']['thumbnail'] ?? null,
                        'price'        => rand(5, 50),
                    ]);
                }
            }

            // Authors
            if (!empty($volume['authors'])) {
                $authorIds = [];
                foreach ($volume['authors'] as $authorName) {
                    $author = Author::firstOrCreate(['name' => $authorName]);
                    $authorIds[] = $author->id;
                }
                $book->authors()->sync($authorIds);
            }

            $books[] = $book;
        }

        return $books;
    }

    private function extractIsbn(array $volume): string
    {
        if (!empty($volume['industryIdentifiers'])) {
            foreach ($volume['industryIdentifiers'] as $identifier) {
                if ($identifier['type'] === 'ISBN_13') {
                    return $identifier['identifier'];
                }
            }
        }
        return uniqid();
    }
}
