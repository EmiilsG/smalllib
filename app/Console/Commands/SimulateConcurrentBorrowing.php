<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimulateConcurrentBorrowing extends Command
{
    protected $signature = 'borrow:attempt {book_id} {reader_id} {delay=0}';
    protected $description = 'Attempt to borrow a book. Used for concurrency testing.';

    public function handle()
    {
        $bookId = (int) $this->argument('book_id');
        $readerId = (int) $this->argument('reader_id');
        $delay = (int) $this->argument('delay');

        if ($delay > 0) {
            usleep($delay * 1000);
        }

        try {
            DB::transaction(function () use ($bookId, $readerId) {
                $affected = DB::update(
                    'UPDATE books SET available_copies = available_copies - 1 WHERE id = ? AND available_copies > 0',
                    [$bookId]
                );

                if ($affected === 0) {
                    throw new \RuntimeException('NAV');
                }

                Borrowing::create([
                    'book_id' => $bookId,
                    'reader_id' => $readerId,
                    'borrowed_at' => now()->format('Y-m-d'),
                    'due_at' => now()->addDays(14)->format('Y-m-d'),
                ]);
            });

            $this->output->writeln('OK');
        } catch (\RuntimeException) {
            $this->output->writeln('NAV');
        }
    }
}
