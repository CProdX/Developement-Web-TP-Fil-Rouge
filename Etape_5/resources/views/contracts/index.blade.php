@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">CONTRATS</h2>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Client</th>
            <th>Heures incluses</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($contracts as $contract)
            @php($client = collect($clients)->firstWhere('id', $contract['client_id']))
            <tr>
                <td>#{{ $contract['id'] }}</td>
                <td>{{ $contract['name'] }}</td>
                <td>{{ $client['name'] ?? 'N/A' }}</td>
                <td>{{ $contract['included_hours'] }} h</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

