<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $borrowings = [
            ['book_id' => 1, 'reader_id' => 1, 'borrowed_at' => '2026-05-01', 'due_at' => '2026-05-15', 'returned_at' => '2026-05-14'],
            ['book_id' => 2, 'reader_id' => 2, 'borrowed_at' => '2026-05-10', 'due_at' => '2026-05-24', 'returned_at' => null],
            ['book_id' => 3, 'reader_id' => 3, 'borrowed_at' => '2026-05-15', 'due_at' => '2026-05-29', 'returned_at' => null],
            ['book_id' => 4, 'reader_id' => 4, 'borrowed_at' => '2026-05-20', 'due_at' => '2026-06-03', 'returned_at' => null],
            ['book_id' => 1, 'reader_id' => 5, 'borrowed_at' => '2026-05-25', 'due_at' => '2026-06-08', 'returned_at' => null],
        ];

        DB::transaction(function () use ($borrowings) {
            foreach ($borrowings as $data) {
                $isReturned = isset($data['returned_at']) && $data['returned_at'] !== null;

                if (!$isReturned) {
                    $affected = DB::update(
                        'UPDATE books SET available_copies = available_copies - 1 WHERE id = ? AND available_copies > 0',
                        [$data['book_id']]
                    );

                    if ($affected === 0) {
                        throw new \RuntimeException('Seeder: nav pieejamu eksemplāru grāmatai #' . $data['book_id']);
                    }
                }

                Borrowing::create($data);
            }
        });
    }
}
