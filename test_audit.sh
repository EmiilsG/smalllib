#!/bin/bash
# Žurnāla (audit log) demonstrācija
set +e

cd "$(dirname "$0")"
export DB_DATABASE=database/testing.sqlite

echo "=== Grāmatu žurnāla (AUDIT LOG) demonstrācija ==="
echo ""

# 1. Iztīra DB un uzstāda svaigus datus
echo "1. Sagatavoju datubāzi..."
php artisan migrate:fresh --quiet 2>/dev/null

# 2. Izveido grāmatu un lasītāju
echo "2. Izveidoju grāmatu un lasītāju..."
BOOK_ID=$(php artisan tinker --execute="echo \App\Models\Book::create(['title' => 'Trigeru testa grāmata', 'isbn' => '978-TRIGGER-01', 'total_copies' => 3, 'available_copies' => 3])->id;" 2>/dev/null)
echo "   Grāmata #$BOOK_ID: 3 eksemplāri"

READER_ID=$(php artisan tinker --execute="echo \App\Models\Reader::create(['name' => 'Testa Lasītājs', 'email' => 'test@example.com'])->id;" 2>/dev/null)
echo "   Lasītājs #$READER_ID"
echo ""

# 3. Parāda žurnālu pēc izveides
echo "3. Žurnāls pēc grāmatas izveides:"
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$logs = DB::table('book_log')->where('book_id', $BOOK_ID)->get();
foreach (\$logs as \$log) {
    echo '   ['. \$log->created_at .'] ' . str_pad(\$log->action, 8) . ' | pieejami: ' . (\$log->old_available_copies ?? '-') . ' → ' . (\$log->new_available_copies ?? '-') . ' | kopā: ' . (\$log->old_total_copies ?? '-') . ' → ' . (\$log->new_total_copies ?? '-') . PHP_EOL;
}
" 2>/dev/null
echo ""

# 4. Aizņemas grāmatu (caur HTTP, lai trigerētu trigeri)
echo "4. Aizņemos grāmatu (lasītājs #$READER_ID aizņemas)..."
php artisan tinker --execute="
\App\Models\Borrowing::create(['book_id' => $BOOK_ID, 'reader_id' => $READER_ID, 'borrowed_at' => '2026-05-27', 'due_at' => '2026-06-10']);
echo '   Aizņēmums izveidots!';
" 2>/dev/null
# Piezīme: trigeris log_book_update fiksēs 'borrow' (jo available_copies samazinās)
# Bet aizņēmums tiek veikts caur tinker, nevis controller, tāpēc available_copies netiek
# samazināts. Izmantosim atomāro UPDATE.
php artisan tinker --execute="
\$affected = Illuminate\Support\Facades\DB::update('UPDATE books SET available_copies = available_copies - 1 WHERE id = ? AND available_copies > 0', [$BOOK_ID]);
echo '   Atjaunināts: ' . \$affected . ' rinda(s)\n';
" 2>/dev/null
echo ""

# 5. Parāda žurnālu pēc aizņemšanās
echo "5. Žurnāls pēc aizņemšanās:"
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$logs = DB::table('book_log')->where('book_id', $BOOK_ID)->orderBy('id')->get();
foreach (\$logs as \$log) {
    echo '   ['. \$log->created_at .'] ' . str_pad(\$log->action, 8) . ' | pieejami: ' . (\$log->old_available_copies ?? '-') . ' → ' . (\$log->new_available_copies ?? '-') . ' | kopā: ' . (\$log->old_total_copies ?? '-') . ' → ' . (\$log->new_total_copies ?? '-') . PHP_EOL;
}
" 2>/dev/null
echo ""

# 6. Atgriež grāmatu
echo "6. Atgriežu grāmatu..."
php artisan tinker --execute="
\$b = \App\Models\Borrowing::where('book_id', $BOOK_ID)->first();
\$b->update(['returned_at' => now()]);
echo '   Grāmata atgriezta! (trigeris restore_available_on_return palielināja pieejamos)';
" 2>/dev/null
echo ""
echo ""

# 7. Parāda žurnālu
echo "7. Žurnāls pēc atgriešanas:"
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$logs = DB::table('book_log')->where('book_id', $BOOK_ID)->orderBy('id')->get();
foreach (\$logs as \$log) {
    echo '   ['. \$log->created_at .'] ' . str_pad(\$log->action, 8) . ' | pieejami: ' . (\$log->old_available_copies ?? '-') . ' → ' . (\$log->new_available_copies ?? '-') . ' | kopā: ' . (\$log->old_total_copies ?? '-') . ' → ' . (\$log->new_total_copies ?? '-') . PHP_EOL;
}
" 2>/dev/null
echo ""

# 8. Rediģē grāmatu (manuāla atjaunināšana)
echo "8. Rediģēju grāmatu (palielinu eksemplāru skaitu)..."
php artisan tinker --execute="
\$b = \App\Models\Book::find($BOOK_ID);
\$b->update(['total_copies' => 5, 'available_copies' => 5]);
echo '   Grāmata atjaunināta!';
" 2>/dev/null
echo ""
echo ""

# 9. Parāda žurnālu
echo "9. Žurnāls pēc rediģēšanas:"
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$logs = DB::table('book_log')->where('book_id', $BOOK_ID)->orderBy('id')->get();
foreach (\$logs as \$log) {
    echo '   ['. \$log->created_at .'] ' . str_pad(\$log->action, 8) . ' | pieejami: ' . (\$log->old_available_copies ?? '-') . ' → ' . (\$log->new_available_copies ?? '-') . ' | kopā: ' . (\$log->old_total_copies ?? '-') . ' → ' . (\$log->new_total_copies ?? '-') . PHP_EOL;
}
" 2>/dev/null
echo ""

# 10. Dzēš grāmatu
echo "10. Dzēšu grāmatu..."
php artisan tinker --execute="
\App\Models\Book::find($BOOK_ID)->delete();
echo '   Grāmata dzēsta!';
" 2>/dev/null
echo ""
echo ""

# 11. Parāda žurnālu
echo "11. Žurnāls pēc dzēšanas (pilns audita pieraksts):"
php artisan tinker --execute="
use Illuminate\Support\Facades\DB;
\$logs = DB::table('book_log')->where('book_id', $BOOK_ID)->orderBy('id')->get();
foreach (\$logs as \$log) {
    echo '   ['. \$log->created_at .'] ' . str_pad(\$log->action, 8) . ' | pieejami: ' . (\$log->old_available_copies ?? '-') . ' → ' . (\$log->new_available_copies ?? '-') . ' | kopā: ' . (\$log->old_total_copies ?? '-') . ' → ' . (\$log->new_total_copies ?? '-') . PHP_EOL;
}
echo PHP_EOL;
echo '   Kopsavilkums: ' . count(\$logs) . ' ieraksti grāmatai #' . $BOOK_ID . PHP_EOL;
echo '   Darbības: ';
echo implode(' → ', \$logs->pluck('action')->toArray()) . PHP_EOL;
" 2>/dev/null
echo ""

echo "=== Demonstrācija pabeigta ==="
echo "Trigeri automātiski fiksē VISAS izmaiņas book_log tabulā."
echo "Pat ja izmaiņas veiktas tieši datubāzē, apejot programmu."
