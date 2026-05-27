<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS active_borrowings AS
            SELECT
                b.id AS borrowing_id,
                bk.title AS book_title,
                bk.isbn,
                r.name AS reader_name,
                r.email AS reader_email,
                b.borrowed_at,
                b.due_at,
                julianday(b.due_at) - julianday('now') AS days_until_due
            FROM borrowings b
            JOIN books bk ON bk.id = b.book_id
            JOIN readers r ON r.id = b.reader_id
            WHERE b.returned_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS active_borrowings");
    }
};
