@extends('layouts.app')

@section('title', 'Sods - ' . $sods['lasitaja_vards'])

@section('content')
    <div class="mb-4">
        <a href="{{ route('sodi.index') }}" class="text-blue-600 hover:underline">&larr; Visi sodi</a>
    </div>

    <div class="bg-white rounded shadow p-6 max-w-lg">
        <h1 class="text-2xl font-bold text-purple-800 mb-4">{{ $sods['lasitaja_vards'] }}</h1>

        @if (isset($sods['error']))
            <p class="text-red-600">{{ $sods['error'] }}</p>
        @else
            <dl class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <dt class="text-gray-600">E-pasts</dt>
                    <dd>{{ $sods['lasitaja_epasts'] }}</dd>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <dt class="text-gray-600">Kavētie aizņēmumi</dt>
                    <dd>{{ $sods['kaveto_aiznemumu_skaits'] }}</dd>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <dt class="text-gray-600">Kavējuma dienas kopā</dt>
                    <dd>{{ $sods['kopejas_kavejuma_dienas'] }}</dd>
                </div>
                <div class="flex justify-between border-b pb-2 text-lg font-bold">
                    <dt class="text-purple-800">Sods (€0.50/dienā)</dt>
                    <dd class="text-purple-800">€{{ number_format($sods['soda_nauda'], 2) }}</dd>
                </div>
            </dl>
        @endif
    </div>
@endsection
