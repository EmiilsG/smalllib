@extends('layouts.app')

@section('title', 'Sodi par kavējumiem')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-purple-800">Sodi par kavējumiem</h1>
        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
            €{{ number_format($sodi->sum('soda_nauda'), 2) }} kopā
        </span>
    </div>

    @if ($sodi->isEmpty())
        <div class="bg-white rounded shadow p-8 text-center text-gray-500">
            Nav sodījamo lasītāju!
        </div>
    @else
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Lasītājs</th>
                        <th class="px-4 py-2 text-center">Kavētie aizņēmumi</th>
                        <th class="px-4 py-2 text-center">Kavējuma dienas</th>
                        <th class="px-4 py-2 text-right">Sods (€0.50/dienā)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sodi as $s)
                        <tr class="border-t hover:bg-purple-50">
                            <td class="px-4 py-2">
                                <a href="{{ route('sodi.show', $s->lasitaja_id) }}" class="text-blue-600 hover:underline">
                                    {{ $s->lasitaja_vards }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $s->lasitaja_epasts }}</div>
                            </td>
                            <td class="px-4 py-2 text-center">{{ $s->kaveto_aiznemumu_skaits }}</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $s->kopejas_kavejuma_dienas }}</td>
                            <td class="px-4 py-2 text-right font-bold text-purple-800">
                                €{{ number_format($s->soda_nauda, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="bg-purple-50 border border-purple-200 rounded p-4 mt-6 text-sm text-purple-800">
        <strong>Soda procedūra:</strong> €0.50 × kavējuma dienas.
        Aprēķins veikts datubāzes skatā <code>lasitaja_sodi</code>.
        Atsevišķam lasītājam <code>SodaProcedure::aprekinatSodu(ID)</code>.
    </div>
@endsection
