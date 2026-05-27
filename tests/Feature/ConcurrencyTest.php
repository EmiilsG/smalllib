<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Process\Process;
use Tests\TestCase;

class ConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_borrowing_last_copy_then_second_fails(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        $reader1 = Reader::factory()->create();
        $reader2 = Reader::factory()->create();

        $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader1->id,
            'borrowed_at' => now()->format('Y-m-d'),
            'due_at' => now()->addDays(14)->format('Y-m-d'),
        ])->assertRedirect(route('borrowings.index'));

        $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader2->id,
            'borrowed_at' => now()->format('Y-m-d'),
            'due_at' => now()->addDays(14)->format('Y-m-d'),
        ])->assertSessionHasErrors('book_id');

        $this->assertEquals(1, Borrowing::count());
        $book->refresh();
        $this->assertEquals(0, $book->available_copies);
    }

    public function test_available_copies_decremented_on_borrow(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 3,
            'available_copies' => 3,
        ]);

        $reader = Reader::factory()->create();

        $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader->id,
            'borrowed_at' => now()->format('Y-m-d'),
            'due_at' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $book->refresh();
        $this->assertEquals(2, $book->available_copies);
    }

    public function test_available_copies_incremented_on_return(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        $borrowing = Borrowing::factory()->create([
            'book_id' => $book->id,
            'returned_at' => null,
        ]);

        $book->refresh();
        $this->assertEquals(0, $book->available_copies);

        $this->patch(route('borrowings.return', $borrowing));

        $book->refresh();
        $this->assertEquals(1, $book->available_copies);
    }

    public function test_borrow_after_return_reuses_copy(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 1,
            'available_copies' => 1,
        ]);

        $borrowing = Borrowing::factory()->create([
            'book_id' => $book->id,
            'returned_at' => null,
        ]);

        $this->patch(route('borrowings.return', $borrowing));

        $reader2 = Reader::factory()->create();
        $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader2->id,
            'borrowed_at' => now()->format('Y-m-d'),
            'due_at' => now()->addDays(14)->format('Y-m-d'),
        ])->assertRedirect(route('borrowings.index'));

        $this->assertEquals(2, Borrowing::count());
        $book->refresh();
        $this->assertEquals(0, $book->available_copies);
    }
}
