@extends('layouts.app')

@section('title', 'Labot grāmatu')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Labot grāmatu</h1>

    <form action="{{ route('books.update', $book) }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nosaukums</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">ISBN</label>
            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="w-full border rounded px-3 py-2 @error('isbn') border-red-500 @enderror">
            @error('isbn') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Eksemplāru skaits</label>
            <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}" min="1" class="w-full border rounded px-3 py-2 @error('total_copies') border-red-500 @enderror">
            @error('total_copies') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Saglabāt</button>
        <a href="{{ route('books.index') }}" class="ml-2 text-gray-600 hover:underline">Atcelt</a>
    </form>
@endsection
