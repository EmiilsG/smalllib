<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'isbn' => fake()->unique()->isbn13(),
            'total_copies' => fake()->numberBetween(1, 5),
            'available_copies' => fake()->numberBetween(0, 5),
        ];
    }
}
