<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    public function test_borrowing_index_page_loads(): void
    {
        Borrowing::factory()->create();

        $response = $this->get(route('borrowings.index'));

        $response->assertStatus(200);
    }

    public function test_borrowing_create_page_loads(): void
    {
        Book::factory()->create(['available_copies' => 1]);
        Reader::factory()->create();

        $response = $this->get(route('borrowings.create'));

        $response->assertStatus(200);
    }

    public function test_borrowing_can_be_stored(): void
    {
        $book = Book::factory()->create(['total_copies' => 2, 'available_copies' => 2]);
        $reader = Reader::factory()->create();

        $response = $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader->id,
            'borrowed_at' => '2026-05-01',
            'due_at' => '2026-05-15',
        ]);

        $response->assertRedirect(route('borrowings.index'));
        $this->assertDatabaseHas('borrowings', ['book_id' => $book->id, 'reader_id' => $reader->id, 'returned_at' => null]);
    }

    public function test_borrowing_cannot_be_stored_when_no_copies_available(): void
    {
        $book = Book::factory()->create(['total_copies' => 1, 'available_copies' => 0]);
        $reader = Reader::factory()->create();

        $response = $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader->id,
            'borrowed_at' => '2026-05-01',
            'due_at' => '2026-05-15',
        ]);

        $response->assertSessionHasErrors('book_id');
    }

    public function test_borrowing_show_page_loads(): void
    {
        $borrowing = Borrowing::factory()->create();

        $response = $this->get(route('borrowings.show', $borrowing));

        $response->assertStatus(200);
    }

    public function test_borrowing_can_be_returned(): void
    {
        $borrowing = Borrowing::factory()->create(['returned_at' => null]);

        $response = $this->patch(route('borrowings.return', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        $this->assertNotNull($borrowing->fresh()->returned_at);
    }

    public function test_borrowing_can_be_deleted(): void
    {
        $borrowing = Borrowing::factory()->create();

        $response = $this->delete(route('borrowings.destroy', $borrowing));

        $response->assertRedirect(route('borrowings.index'));
        $this->assertDatabaseMissing('borrowings', ['id' => $borrowing->id]);
    }
}
