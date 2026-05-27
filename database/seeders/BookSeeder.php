<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['title' => 'Ceļojums uz zvaigznēm', 'isbn' => '978-1-234-56789-0', 'total_copies' => 3],
            ['title' => 'Noslēpumainā sala', 'isbn' => '978-1-234-56789-1', 'total_copies' => 2],
            ['title' => 'Programmēšanas pamati', 'isbn' => '978-1-234-56789-2', 'total_copies' => 5],
            ['title' => 'Datu bāzu māksla', 'isbn' => '978-1-234-56789-3', 'total_copies' => 4],
            ['title' => 'Alķīmiķis', 'isbn' => '978-1-234-56789-4', 'total_copies' => 2],
            ['title' => 'Mazais princis', 'isbn' => '978-1-234-56789-5', 'total_copies' => 3],
            ['title' => 'Lielais Gatsby', 'isbn' => '978-1-234-56789-6', 'total_copies' => 1],
            ['title' => '1984', 'isbn' => '978-1-234-56789-7', 'total_copies' => 4],
            ['title' => 'Lauku sēta', 'isbn' => '978-1-234-56789-8', 'total_copies' => 2],
            ['title' => 'Dinozauru laikmets', 'isbn' => '978-1-234-56789-9', 'total_copies' => 3],
        ];

        foreach ($books as $book) {
            Book::create($book + ['available_copies' => $book['total_copies']]);
        }
    }
}
