<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION zurnals_pc_gramatas_atjauninasanas_func()
            RETURNS TRIGGER AS $$
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
                     CURRENT_TIMESTAMP);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::statement("
            DROP TRIGGER IF EXISTS zurnals_pc_gramatas_atjauninasanas ON books
        ");

        DB::statement("
            CREATE TRIGGER zurnals_pc_gramatas_atjauninasanas
            AFTER UPDATE ON books
            FOR EACH ROW
            EXECUTE FUNCTION zurnals_pc_gramatas_atjauninasanas_func();
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS zurnals_pc_gramatas_atjauninasanas ON books");
        DB::statement("DROP FUNCTION IF EXISTS zurnals_pc_gramatas_atjauninasanas_func()");
    }
};
