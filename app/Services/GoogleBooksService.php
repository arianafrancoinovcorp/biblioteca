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

            $book = Book::firstWhere('google_id', $googleId);

            if (!$book) {
                $publisherName = $volume['publisher'] ?? 'Unknown';
                $publisher = Publisher::firstOrCreate(['name' => $publisherName]);

                $existingBook = Book::where('name', $title)
                                    ->where('publisher_id', $publisher->id)
                                    ->first();
                if ($existingBook) {
                    $book = $existingBook; 
                } else {
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
