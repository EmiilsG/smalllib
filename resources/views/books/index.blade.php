@extends('layouts.app')

@section('title', 'Grāmatas')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Grāmatas</h1>
        <a href="{{ route('books.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Pievienot grāmatu</a>
    </div>

    <form method="GET" action="{{ route('books.index') }}" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Meklēt grāmatas pēc nosaukuma vai ISBN..." class="flex-1 border rounded px-3 py-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Meklēt</button>
            @if (request('q'))
                <a href="{{ route('books.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Notīrīt</a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Nosaukums</th>
                    <th class="px-4 py-2 text-left">ISBN</th>
                    <th class="px-4 py-2 text-center">Pieejami</th>
                    <th class="px-4 py-2 text-center">Kopā</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:underline">{{ $book->title }}</a>
                        </td>
                        <td class="px-4 py-2 text-gray-600">{{ $book->isbn }}</td>
                        <td class="px-4 py-2 text-center">{{ $book->available_copies }}</td>
                        <td class="px-4 py-2 text-center">{{ $book->total_copies }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('books.edit', $book) }}" class="text-yellow-600 hover:underline mr-2">Labot</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Dzēst?')">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $books->links() }}</div>
@endsection
