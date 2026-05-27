<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW kavetie_aiznemumi AS
            SELECT
                b.id AS aiznemuma_id,
                bk.id AS gramatas_id,
                bk.title AS gramatas_nosaukums,
                bk.isbn,
                r.id AS lasitaja_id,
                r.name AS lasitaja_vards,
                r.email AS lasitaja_epasts,
                b.borrowed_at AS aiznemts,
                b.due_at AS jaatdod,
                (CURRENT_DATE - b.due_at) AS kavejuma_dienas
            FROM borrowings b
            JOIN books bk ON bk.id = b.book_id
            JOIN readers r ON r.id = b.reader_id
            WHERE b.returned_at IS NULL
              AND b.due_at < CURRENT_DATE
            ORDER BY b.due_at ASC
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS kavetie_aiznemumi");
    }
};
