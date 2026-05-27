@extends('layouts.app')

@section('title', 'Kavētie aizņēmumi')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-red-800">Kavētie aizņēmumi</h1>
        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
            {{ $kavetie->count() }} kavēti
        </span>
    </div>

    @if ($kavetie->isEmpty())
        <div class="bg-white rounded shadow p-8 text-center text-gray-500">
            Nav kavētu aizņēmumu!
        </div>
    @else
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-red-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Grāmata</th>
                        <th class="px-4 py-2 text-left">Lasītājs</th>
                        <th class="px-4 py-2 text-center">Aizņemts</th>
                        <th class="px-4 py-2 text-center">Jāatdod</th>
                        <th class="px-4 py-2 text-center">Kavējums</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kavetie as $k)
                        <tr class="border-t hover:bg-red-50">
                            <td class="px-4 py-2">
                                <a href="{{ route('books.show', $k->gramatas_id) }}" class="text-blue-600 hover:underline">
                                    {{ $k->gramatas_nosaukums }}
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('readers.show', $k->lasitaja_id) }}" class="text-blue-600 hover:underline">
                                    {{ $k->lasitaja_vards }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $k->lasitaja_epasts }}</div>
                            </td>
                            <td class="px-4 py-2 text-center">{{ $k->aiznemts }}</td>
                            <td class="px-4 py-2 text-center font-medium">{{ $k->jaatdod }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-bold">
                                    {{ $k->kavejuma_dienas }} dienas
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <p class="text-sm text-gray-500 mt-4">
        Dati no datubāzes skata <code>kavetie_aiznemumi</code> — automātiski atjaunojas.
    </p>
@endsection
