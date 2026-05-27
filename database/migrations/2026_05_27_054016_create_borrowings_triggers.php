<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TRIGGER IF NOT EXISTS restore_available_on_return
            AFTER UPDATE OF returned_at ON borrowings
            FOR EACH ROW
            WHEN NEW.returned_at IS NOT NULL AND OLD.returned_at IS NULL
            BEGIN
                UPDATE books
                SET available_copies = available_copies + 1
                WHERE id = NEW.book_id;
            END
        ");

        DB::statement("
            CREATE TRIGGER IF NOT EXISTS restore_available_on_delete
            AFTER DELETE ON borrowings
            FOR EACH ROW
            WHEN OLD.returned_at IS NULL
            BEGIN
                UPDATE books
                SET available_copies = available_copies + 1
                WHERE id = OLD.book_id;
            END
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS restore_available_on_return");
        DB::statement("DROP TRIGGER IF EXISTS restore_available_on_delete");
    }
};
