<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookRequests;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookRequestsFactory extends Factory
{
    protected $model = BookRequests::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $startDate)->modify('+14 days');

        return [
            'book_id' => Book::factory(),
            'user_id' => User::factory(),
            'status' => 'active',
            'start_date' => $startDate,
            'due_date' => $dueDate,
            'return_date' => null,
            'photo' => null,
            'notes' => $this->faker->sentence(),
        ];
    }
}
