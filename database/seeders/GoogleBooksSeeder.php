<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;

class GoogleBooksSeeder extends Seeder
{
    public function run()
    {
        $searchTerms = ['Harry Potter', 'Lord of the Rings', 'Game of Thrones'];

        foreach ($searchTerms as $term) {
            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'q' => $term,
                'maxResults' => 5,
                'key' => env('GOOGLE_BOOKS_API_KEY'),
            ]);

            if ($response->successful()) {
                $booksData = $response->json()['items'] ?? [];

                foreach ($booksData as $item) {
                    $volume = $item['volumeInfo'];

                    $isbn = null;
                    if (!empty($volume['industryIdentifiers'])) {
                        foreach ($volume['industryIdentifiers'] as $id) {
                            if ($id['type'] === 'ISBN_13') {
                                $isbn = $id['identifier'];
                                break;
                            }
                        }
                    }

                    if (!$isbn) continue; 

                    // publisher
                    $publisher = null;
                    if (!empty($volume['publisher'])) {
                        $publisher = Publisher::firstOrCreate([
                            'name' => $volume['publisher']
                        ]);
                    }

                    // Books
                    $book = Book::firstOrCreate(
                        ['isbn' => $isbn],
                        [
                            'name' => $volume['title'] ?? 'Unknown',
                            'publisher_id' => $publisher?->id,
                            'bibliography' => $volume['description'] ?? null,
                            'cover_image' => $volume['imageLinks']['thumbnail'] ?? null,
                            'price' => rand(10, 50), 
                        ]
                    );

                    // Authors
                    if (!empty($volume['authors'])) {
                        $authorIds = [];
                        foreach ($volume['authors'] as $authorName) {
                            $author = Author::firstOrCreate(['name' => $authorName]);
                            $authorIds[] = $author->id;
                        }
                        $book->authors()->sync($authorIds);
                    }
                }
            }
        }

        $this->command->info('Books imported from Google Books API!');
    }
}
