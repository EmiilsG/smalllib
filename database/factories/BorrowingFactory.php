<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Reader;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'reader_id' => Reader::factory(),
            'borrowed_at' => now()->subDays(rand(1, 30))->format('Y-m-d'),
            'due_at' => now()->addDays(rand(1, 30))->format('Y-m-d'),
            'returned_at' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($borrowing) {
            if ($borrowing->returned_at === null) {
                $borrowing->book()->decrement('available_copies');
            }
        });
    }
}
