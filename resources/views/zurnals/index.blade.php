@extends('layouts.app')

@section('title', 'Žurnāls')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Žurnāls</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Laiks</th>
                    <th class="px-4 py-2 text-left">Grāmata</th>
                    <th class="px-4 py-2 text-left">Darbība</th>
                    <th class="px-4 py-2 text-center">Pieejami (vecais → jaunais)</th>
                    <th class="px-4 py-2 text-center">Kopā (vecais → jaunais)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $log->izmainits }}</td>
                        <td class="px-4 py-2">
                            @if ($log->gramatas_nosaukums)
                                <a href="{{ route('books.show', $log->gramatas_id) }}" class="text-blue-600 hover:underline">
                                    {{ $log->gramatas_nosaukums }}
                                </a>
                            @else
                                <span class="text-gray-400">Grāmata #{{ $log->gramatas_id }} (dzēsta)</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @switch($log->darbiba)
                                @case('aiznemsana')
                                    <span class="text-orange-600 font-medium">Aizņemšanās</span>
                                    @break
                                @case('atgriesana')
                                    <span class="text-green-600 font-medium">Atgriešana</span>
                                    @break
                                @case('labosana')
                                    <span class="text-blue-600 font-medium">Labošana</span>
                                    @break
                                @default
                                    <span class="text-gray-600">{{ $log->darbiba }}</span>
                            @endswitch
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $log->vecais_pieejams ?? '—' }} → {{ $log->jaunais_pieejams ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            {{ $log->vecais_kopa ?? '—' }} → {{ $log->jaunais_kopa ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Žurnāls ir tukšs.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
