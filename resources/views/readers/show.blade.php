@extends('layouts.app')

@section('title', $reader->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('readers.index') }}" class="text-blue-600 hover:underline">&larr; Atpakaļ</a>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $reader->name }}</h1>
        <p class="text-gray-600">{{ $reader->email }}</p>
    </div>

    <h2 class="text-xl font-bold mb-2">Aizņēmumi</h2>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Grāmata</th>
                    <th class="px-4 py-2 text-left">Aizņemts</th>
                    <th class="px-4 py-2 text-left">Jāatdod</th>
                    <th class="px-4 py-2 text-left">Atgriezts</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reader->borrowings as $borrowing)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $borrowing->book->title }}</td>
                        <td class="px-4 py-2">{{ $borrowing->borrowed_at }}</td>
                        <td class="px-4 py-2">{{ $borrowing->due_at }}</td>
                        <td class="px-4 py-2">{{ $borrowing->returned_at ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
