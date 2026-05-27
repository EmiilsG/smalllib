<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::latest();

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
        }

        $books = $query->paginate(10)->withQueryString();

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'total_copies' => 'required|integer|min:1',
        ]);

        $validated['available_copies'] = $validated['total_copies'];

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Grāmata pievienota!');
    }

    public function show(Book $book): View
    {
        $book->load('borrowings.reader');
        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'total_copies' => 'required|integer|min:1',
        ]);

        $diff = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = $book->available_copies + $diff;

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Grāmata atjaunota!');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Grāmata dzēsta!');
    }
}
