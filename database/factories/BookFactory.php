<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Publishers;

class BookFactory extends Factory
{
    protected $model = \App\Models\Book::class;

    public function definition(): array
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'name' => $this->faker->sentence(3),
            'publisher_id' => \App\Models\Publisher::inRandomOrder()->first()->id ?? \App\Models\Publisher::factory(),
            'bibliography' => $this->faker->paragraph(),
            'cover_image' => 'https://i.pravatar.cc/150?u=' . $this->faker->unique()->uuid,
            'price' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}
