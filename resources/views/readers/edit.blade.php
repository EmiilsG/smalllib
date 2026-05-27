@extends('layouts.app')

@section('title', 'Labot lasītāju')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Labot lasītāju</h1>

    <form action="{{ route('readers.update', $reader) }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Vārds</label>
            <input type="text" name="name" value="{{ old('name', $reader->name) }}" class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">E-pasts</label>
            <input type="email" name="email" value="{{ old('email', $reader->email) }}" class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Saglabāt</button>
        <a href="{{ route('readers.index') }}" class="ml-2 text-gray-600 hover:underline">Atcelt</a>
    </form>
@endsection
