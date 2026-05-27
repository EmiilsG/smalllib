<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TRIGGER IF NOT EXISTS zurnals_pc_gramatas_atjauninasanas
            AFTER UPDATE ON books
            FOR EACH ROW
            BEGIN
                INSERT INTO zurnals
                    (gramatas_id, darbiba,
                     vecais_pieejams, jaunais_pieejams,
                     vecais_kopa, jaunais_kopa,
                     izmainits)
                VALUES
                    (OLD.id,
                     CASE
                         WHEN NEW.available_copies = OLD.available_copies - 1 THEN 'aiznemsana'
                         WHEN NEW.available_copies = OLD.available_copies + 1 THEN 'atgriesana'
                         ELSE 'labosana'
                     END,
                     OLD.available_copies, NEW.available_copies,
                     OLD.total_copies, NEW.total_copies,
                     datetime('now'));
            END
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS zurnals_pc_gramatas_atjauninasanas");
    }
};
