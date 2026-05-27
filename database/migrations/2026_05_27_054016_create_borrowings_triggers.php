<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION restore_available_on_return_func()
            RETURNS TRIGGER AS $$
            BEGIN
                UPDATE books
                SET available_copies = available_copies + 1
                WHERE id = NEW.book_id;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::statement("
            CREATE OR REPLACE FUNCTION restore_available_on_delete_func()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.returned_at IS NULL THEN
                    UPDATE books
                    SET available_copies = available_copies + 1
                    WHERE id = OLD.book_id;
                END IF;
                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::statement("
            DROP TRIGGER IF EXISTS restore_available_on_return ON borrowings
        ");

        DB::statement("
            CREATE TRIGGER restore_available_on_return
            AFTER UPDATE OF returned_at ON borrowings
            FOR EACH ROW
            WHEN (NEW.returned_at IS NOT NULL AND OLD.returned_at IS NULL)
            EXECUTE FUNCTION restore_available_on_return_func();
        ");

        DB::statement("
            DROP TRIGGER IF EXISTS restore_available_on_delete ON borrowings
        ");

        DB::statement("
            CREATE TRIGGER restore_available_on_delete
            AFTER DELETE ON borrowings
            FOR EACH ROW
            WHEN (OLD.returned_at IS NULL)
            EXECUTE FUNCTION restore_available_on_delete_func();
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS restore_available_on_return ON borrowings");
        DB::statement("DROP TRIGGER IF EXISTS restore_available_on_delete ON borrowings");
        DB::statement("DROP FUNCTION IF EXISTS restore_available_on_return_func()");
        DB::statement("DROP FUNCTION IF EXISTS restore_available_on_delete_func()");
    }
};
