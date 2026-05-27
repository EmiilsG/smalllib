<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TRIGGER IF NOT EXISTS log_book_insert
            AFTER INSERT ON books
            FOR EACH ROW
            BEGIN
                INSERT INTO book_log
                    (book_id, action, old_available_copies, new_available_copies,
                     old_total_copies, new_total_copies, created_at, updated_at)
                VALUES
                    (NEW.id, 'create',
                     NULL, NEW.available_copies,
                     NULL, NEW.total_copies,
                     datetime('now'), datetime('now'));
            END
        ");

        DB::statement("
            CREATE TRIGGER IF NOT EXISTS log_book_update
            AFTER UPDATE ON books
            FOR EACH ROW
            BEGIN
                INSERT INTO book_log
                    (book_id, action, old_available_copies, new_available_copies,
                     old_total_copies, new_total_copies, created_at, updated_at)
                VALUES
                    (NEW.id,
                     CASE
                         WHEN OLD.total_copies != NEW.total_copies THEN 'update'
                         WHEN NEW.available_copies = OLD.available_copies - 1 THEN 'borrow'
                         WHEN NEW.available_copies = OLD.available_copies + 1 THEN 'return'
                         ELSE 'update'
                     END,
                     OLD.available_copies, NEW.available_copies,
                     OLD.total_copies, NEW.total_copies,
                     datetime('now'), datetime('now'));
            END
        ");

        DB::statement("
            CREATE TRIGGER IF NOT EXISTS log_book_delete
            AFTER DELETE ON books
            FOR EACH ROW
            BEGIN
                INSERT INTO book_log
                    (book_id, action, old_available_copies, new_available_copies,
                     old_total_copies, new_total_copies, created_at, updated_at)
                VALUES
                    (OLD.id, 'delete',
                     OLD.available_copies, NULL,
                     OLD.total_copies, NULL,
                     datetime('now'), datetime('now'));
            END
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS log_book_insert");
        DB::statement("DROP TRIGGER IF EXISTS log_book_update");
        DB::statement("DROP TRIGGER IF EXISTS log_book_delete");
    }
};
