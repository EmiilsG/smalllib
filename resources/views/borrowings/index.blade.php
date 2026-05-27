@extends('layouts.app')

@section('title', 'Aizņēmumi')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Aizņēmumi</h1>
        <a href="{{ route('borrowings.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Jauns aizņēmums</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Grāmata</th>
                    <th class="px-4 py-2 text-left">Lasītājs</th>
                    <th class="px-4 py-2 text-left">Aizņemts</th>
                    <th class="px-4 py-2 text-left">Jāatdod</th>
                    <th class="px-4 py-2 text-left">Atgriezts</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrowings as $borrowing)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $borrowing->book->title }}</td>
                        <td class="px-4 py-2">{{ $borrowing->reader->name }}</td>
                        <td class="px-4 py-2">{{ $borrowing->borrowed_at }}</td>
                        <td class="px-4 py-2">{{ $borrowing->due_at }}</td>
                        <td class="px-4 py-2">{{ $borrowing->returned_at ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">
                            @if (!$borrowing->returned_at)
                                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:underline mr-2">Atgriezt</button>
                                </form>
                            @endif
                            <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Dzēst?')">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $borrowings->links() }}</div>
@endsection
