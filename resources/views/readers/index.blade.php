@extends('layouts.app')

@section('title', 'Lasītāji')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Lasītāji</h1>
        <a href="{{ route('readers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Pievienot lasītāju</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Vārds</th>
                    <th class="px-4 py-2 text-left">E-pasts</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($readers as $reader)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <a href="{{ route('readers.show', $reader) }}" class="text-blue-600 hover:underline">{{ $reader->name }}</a>
                        </td>
                        <td class="px-4 py-2 text-gray-600">{{ $reader->email }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('readers.edit', $reader) }}" class="text-yellow-600 hover:underline mr-2">Labot</a>
                            <form action="{{ route('readers.destroy', $reader) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Dzēst?')">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $readers->links() }}</div>
@endsection
