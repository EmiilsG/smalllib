<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS lasitaja_sodi AS
            SELECT
                r.id AS lasitaja_id,
                r.name AS lasitaja_vards,
                r.email AS lasitaja_epasts,
                COUNT(b.id) AS kaveto_aiznemumu_skaits,
                SUM(CAST(julianday('now') - julianday(b.due_at) AS INTEGER)) AS kopejas_kavejuma_dienas,
                ROUND(SUM(CAST(julianday('now') - julianday(b.due_at) AS INTEGER)) * 0.50, 2) AS soda_nauda
            FROM readers r
            LEFT JOIN borrowings b ON b.reader_id = r.id
                AND b.returned_at IS NULL
                AND b.due_at < date('now')
            GROUP BY r.id, r.name, r.email
            HAVING kaveto_aiznemumu_skaits > 0
            ORDER BY soda_nauda DESC
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS lasitaja_sodi");
    }
};
