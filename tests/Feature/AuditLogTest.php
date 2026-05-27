<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_trigger_logs_book_creation(): void
    {
        Book::factory()->create([
            'title' => 'Testa grāmata',
            'isbn' => '978-AUDIT-00001',
            'total_copies' => 5,
            'available_copies' => 5,
        ]);

        $log = DB::table('book_log')->where('action', 'create')->first();

        $this->assertNotNull($log);
        $this->assertEquals(5, $log->new_total_copies);
        $this->assertEquals(5, $log->new_available_copies);
        $this->assertNull($log->old_total_copies);
        $this->assertNull($log->old_available_copies);
    }

    public function test_trigger_logs_borrow_action(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 2,
            'available_copies' => 2,
        ]);
        $reader = Reader::factory()->create();

        $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader->id,
            'borrowed_at' => now()->format('Y-m-d'),
            'due_at' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $log = DB::table('book_log')
            ->where('book_id', $book->id)
            ->where('action', 'borrow')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals(2, $log->old_available_copies);
        $this->assertEquals(1, $log->new_available_copies);
    }

    public function test_trigger_logs_return_action(): void
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

        $log = DB::table('book_log')
            ->where('book_id', $book->id)
            ->where('action', 'return')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals(0, $log->old_available_copies);
        $this->assertEquals(1, $log->new_available_copies);
    }

    public function test_trigger_logs_manual_update(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 3,
            'available_copies' => 3,
        ]);

        $book->update(['total_copies' => 5, 'available_copies' => 5]);

        $log = DB::table('book_log')
            ->where('book_id', $book->id)
            ->where('action', 'update')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals(3, $log->old_total_copies);
        $this->assertEquals(5, $log->new_total_copies);
        $this->assertEquals(3, $log->old_available_copies);
        $this->assertEquals(5, $log->new_available_copies);
    }

    public function test_trigger_logs_book_deletion(): void
    {
        $book = Book::factory()->create([
            'total_copies' => 1,
            'available_copies' => 1,
        ]);
        $bookId = $book->id;

        $book->delete();

        $log = DB::table('book_log')
            ->where('book_id', $bookId)
            ->where('action', 'delete')
            ->first();

        $this->assertNotNull($log);
        $this->assertEquals(1, $log->old_total_copies);
        $this->assertEquals(1, $log->old_available_copies);
        $this->assertNull($log->new_total_copies);
        $this->assertNull($log->new_available_copies);
    }

    public function test_full_audit_trail(): void
    {
        $book = Book::factory()->create([
            'title' => 'Auditējama grāmata',
            'isbn' => '978-AUDIT-99999',
            'total_copies' => 2,
            'available_copies' => 2,
        ]);
        $reader = Reader::factory()->create();

        $logsBefore = DB::table('book_log')->where('book_id', $book->id)->count();
        $this->assertEquals(1, $logsBefore); // create

        $this->post(route('borrowings.store'), [
            'book_id' => $book->id,
            'reader_id' => $reader->id,
            'borrowed_at' => now()->subDays(10)->format('Y-m-d'),
            'due_at' => now()->addDays(4)->format('Y-m-d'),
        ]);

        $logsAfterBorrow = DB::table('book_log')->where('book_id', $book->id)->count();
        $this->assertEquals(2, $logsAfterBorrow);

        $borrowing = Borrowing::where('book_id', $book->id)->first();
        $this->patch(route('borrowings.return', $borrowing));

        $logsAfterReturn = DB::table('book_log')->where('book_id', $book->id)->count();
        $this->assertEquals(3, $logsAfterReturn);

        $book->update(['total_copies' => 3]);

        $logsAfterUpdate = DB::table('book_log')->where('book_id', $book->id)->count();
        $this->assertEquals(4, $logsAfterUpdate);

        $actions = DB::table('book_log')
            ->where('book_id', $book->id)
            ->orderBy('id')
            ->pluck('action')
            ->toArray();

        $this->assertEquals(['create', 'borrow', 'return', 'update'], $actions);
    }
}
