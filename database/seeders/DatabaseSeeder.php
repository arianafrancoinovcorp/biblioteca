<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if (\App\Models\Publisher::count() === 0 || Author::count() === 0) {
            $this->command->warn('Publishers e Authors criados antes de correr este seeder.');
            return;
        }

        Book::factory()
            ->count(20)
            ->create()
            ->each(function ($book) {
                // Associa 1 a 3 autores
                $authorIds = Author::inRandomOrder()->take(rand(1, 3))->pluck('id');
                $book->authors()->attach($authorIds);
            });
    }
}
