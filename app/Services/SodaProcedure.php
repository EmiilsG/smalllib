<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SodaProcedure
{
    const CENA_PAR_DIENU = 0.50;

    public static function aprekinatSodu(int $lasitajaId): array
    {
        $result = DB::selectOne("
            SELECT
                r.id AS lasitaja_id,
                r.name AS lasitaja_vards,
                r.email AS lasitaja_epasts,
                COUNT(b.id) AS kaveto_aiznemumu_skaits,
                COALESCE(SUM(CURRENT_DATE - b.due_at), 0) AS kopejas_kavejuma_dienas,
                ROUND(COALESCE(SUM(CURRENT_DATE - b.due_at), 0) * ?, 2) AS soda_nauda
            FROM readers r
            LEFT JOIN borrowings b ON b.reader_id = r.id
                AND b.returned_at IS NULL
                AND b.due_at < CURRENT_DATE
            WHERE r.id = ?
            GROUP BY r.id, r.name, r.email
        ", [self::CENA_PAR_DIENU, $lasitajaId]);

        if (!$result) {
            $reader = DB::table('readers')->find($lasitajaId);
            if (!$reader) {
                return ['error' => "Lasītājs ar ID {$lasitajaId} nav atrasts."];
            }
            return [
                'lasitaja_id' => $lasitajaId,
                'lasitaja_vards' => $reader->name,
                'lasitaja_epasts' => $reader->email,
                'kaveto_aiznemumu_skaits' => 0,
                'kopejas_kavejuma_dienas' => 0,
                'soda_nauda' => 0.00,
            ];
        }

        return (array) $result;
    }

    public static function aprekinatVisusSodus(): array
    {
        $results = DB::select("
            SELECT
                r.id AS lasitaja_id,
                r.name AS lasitaja_vards,
                r.email AS lasitaja_epasts,
                COUNT(b.id) AS kaveto_aiznemumu_skaits,
                COALESCE(SUM(CURRENT_DATE - b.due_at), 0) AS kopejas_kavejuma_dienas,
                ROUND(COALESCE(SUM(CURRENT_DATE - b.due_at), 0) * ?, 2) AS soda_nauda
            FROM readers r
            LEFT JOIN borrowings b ON b.reader_id = r.id
                AND b.returned_at IS NULL
                AND b.due_at < CURRENT_DATE
            GROUP BY r.id, r.name, r.email
            ORDER BY soda_nauda DESC
        ", [self::CENA_PAR_DIENU]);

        return array_map(fn($r) => (array) $r, $results);
    }
}
