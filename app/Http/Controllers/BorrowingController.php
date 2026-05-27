<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(): View
    {
        $borrowings = Borrowing::with(['book', 'reader'])->latest()->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function create(): View
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $readers = Reader::all();
        return view('borrowings.create', compact('books', 'readers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'borrowed_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:borrowed_at',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $affected = DB::update(
                    'UPDATE books SET available_copies = available_copies - 1 WHERE id = ? AND available_copies > 0',
                    [$validated['book_id']]
                );

                if ($affected === 0) {
                    throw new \RuntimeException('Nav pieejamu eksemplāru.');
                }

                Borrowing::create($validated);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['book_id' => $e->getMessage()]);
        }

        return redirect()->route('borrowings.index')->with('success', 'Aizņēmums reģistrēts!');
    }

    public function show(Borrowing $borrowing): View
    {
        $borrowing->load(['book', 'reader']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function returnBook(Borrowing $borrowing): RedirectResponse
    {
        $borrowing->update(['returned_at' => now()]);

        return redirect()->route('borrowings.index')->with('success', 'Grāmata atgriezta!');
    }

    public function destroy(Borrowing $borrowing): RedirectResponse
    {
        $borrowing->delete();

        return redirect()->route('borrowings.index')->with('success', 'Aizņēmums dzēsts!');
    }
}
