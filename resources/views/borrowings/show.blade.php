@extends('layouts.app')

@section('title', 'Aizņēmuma detaļas')

@section('content')
    <div class="mb-4">
        <a href="{{ route('borrowings.index') }}" class="text-blue-600 hover:underline">&larr; Atpakaļ</a>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Aizņēmums #{{ $borrowing->id }}</h1>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Grāmata</dt>
                <dd class="font-medium">{{ $borrowing->book->title }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Lasītājs</dt>
                <dd class="font-medium">{{ $borrowing->reader->name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Aizņemts</dt>
                <dd>{{ $borrowing->borrowed_at }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Jāatdod</dt>
                <dd>{{ $borrowing->due_at }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Atgriezts</dt>
                <dd>{{ $borrowing->returned_at ?? 'Vēl nav atgriezts' }}</dd>
            </div>
        </dl>
    </div>
@endsection
