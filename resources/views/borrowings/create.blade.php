@extends('layouts.app')

@section('title', 'Jauns aizņēmums')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Jauns aizņēmums</h1>

    <form action="{{ route('borrowings.store') }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Grāmata</label>
            <select name="book_id" class="w-full border rounded px-3 py-2 @error('book_id') border-red-500 @enderror">
                <option value="">— Izvēlies —</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} ({{ $book->available_copies }} pieejami)
                    </option>
                @endforeach
            </select>
            @error('book_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Lasītājs</label>
            <select name="reader_id" class="w-full border rounded px-3 py-2 @error('reader_id') border-red-500 @enderror">
                <option value="">— Izvēlies —</option>
                @foreach ($readers as $reader)
                    <option value="{{ $reader->id }}" {{ old('reader_id') == $reader->id ? 'selected' : '' }}>
                        {{ $reader->name }}
                    </option>
                @endforeach
            </select>
            @error('reader_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Aizņemšanas datums</label>
            <input type="date" name="borrowed_at" value="{{ old('borrowed_at', date('Y-m-d')) }}" class="w-full border rounded px-3 py-2 @error('borrowed_at') border-red-500 @enderror">
            @error('borrowed_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Atdošanas datums</label>
            <input type="date" name="due_at" value="{{ old('due_at', date('Y-m-d', strtotime('+14 days'))) }}" class="w-full border rounded px-3 py-2 @error('due_at') border-red-500 @enderror">
            @error('due_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Izveidot</button>
        <a href="{{ route('borrowings.index') }}" class="ml-2 text-gray-600 hover:underline">Atcelt</a>
    </form>
@endsection
