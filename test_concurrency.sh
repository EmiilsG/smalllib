#!/bin/bash
set +e

echo "=== Konkurences tests ==="
echo ""

cd "$(dirname "$0")"
export DB_DATABASE=database/testing.sqlite

echo "1. Sagatavoju testa datus..."
php artisan migrate:fresh --quiet 2>/dev/null

BOOK_ID=$(php artisan tinker --execute="echo \App\Models\Book::create(['title' => 'Konkurences tests', 'isbn' => '978-TEST-CONCUR-01', 'total_copies' => 3, 'available_copies' => 3])->id;" 2>/dev/null)
echo "   Grāmata #$BOOK_ID ar 3 eksemplāriem"

for i in $(seq 1 10); do
    php artisan tinker --execute="\App\Models\Reader::create(['name' => 'Lasītājs #$i', 'email' => 'lasitajs$i@example.com']);" --quiet 2>/dev/null
done
echo "   10 lasītāji izveidoti"
echo ""

echo "2. Simulēju 10 vienlaicīgus aizņēmuma mēģinājumus..."
echo ""

PIDS=()
for i in $(seq 1 10); do
    php artisan borrow:attempt "$BOOK_ID" "$i" 0 2>/dev/null &
    PIDS+=($!)
done

for pid in "${PIDS[@]}"; do
    wait "$pid" 2>/dev/null
done

echo ""
echo "   Visi mēģinājumi pabeigti."
echo ""

echo "3. Rezultāti:"
echo ""

TOTAL_BORROWINGS=$(php artisan tinker --execute="echo \App\Models\Borrowing::count();" 2>/dev/null)
BOOK_STATE=$(php artisan tinker --execute="\$b = \App\Models\Book::find($BOOK_ID); echo \$b->available_copies . '/' . \$b->total_copies;" 2>/dev/null)

echo "   Izdevās: $TOTAL_BORROWINGS"
echo "   Neizdevās: $((10 - TOTAL_BORROWINGS))"
echo "   Grāmata: $BOOK_STATE pieejami/kopā"
echo ""

if [ "$TOTAL_BORROWINGS" -eq 3 ] && echo "$BOOK_STATE" | grep -q "^0/"; then
    echo "✓ DATI NAV BOJĀTI: transakcijas novērš konkurences problēmas!"
    echo "  Precīzi 3 aizņēmumi no 10 mēģinājumiem, paliek 0 eksemplāri."
    exit 0
else
    echo "✗ KONKURENCES PROBLĒMA!"
    echo "  Sagaidīju 3 aizņēmumus un 0 pieejamus eksemplārus."
    echo "  Dabūju: $TOTAL_BORROWINGS aizņēmumi, grāmata: $BOOK_STATE"
    exit 1
fi
