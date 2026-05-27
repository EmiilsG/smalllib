@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="mb-4">
        <a href="{{ route('books.index') }}" class="text-blue-600 hover:underline">&larr; Atpakaļ</a>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $book->title }}</h1>
        <p class="text-gray-600">ISBN: {{ $book->isbn }}</p>
        <p class="text-gray-600">Pieejami: {{ $book->available_copies }} / {{ $book->total_copies }}</p>
    </div>

    <h2 class="text-xl font-bold mb-2">Aizņēmumu vēsture</h2>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Lasītājs</th>
                    <th class="px-4 py-2 text-left">Aizņemts</th>
                    <th class="px-4 py-2 text-left">Jāatdod</th>
                    <th class="px-4 py-2 text-left">Atgriezts</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($book->borrowings as $borrowing)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $borrowing->reader->name }}</td>
                        <td class="px-4 py-2">{{ $borrowing->borrowed_at }}</td>
                        <td class="px-4 py-2">{{ $borrowing->due_at }}</td>
                        <td class="px-4 py-2">{{ $borrowing->returned_at ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
