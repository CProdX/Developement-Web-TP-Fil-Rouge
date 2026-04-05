@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">CLIENTS</h2>

    <form class="barre-filtres" method="GET" action="{{ route('clients.index') }}">
        <div class="filtre-groupe">
            <label for="recherche">Rechercher</label>
            <input type="text" id="recherche" name="q" value="{{ request('q') }}" placeholder="Nom ou email...">
        </div>
        <button type="submit" class="bouton-rechercher">Filtrer</button>
    </form>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Heures incluses</th>
            <th>Heures restantes</th>
            <th>Heures a facturer</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($clients as $client)
            <tr>
                <td>#{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ number_format($client->included_budget, 2, ',', ' ') }} h</td>
                <td>{{ number_format($client->hours_remaining, 2, ',', ' ') }} h</td>
                <td>{{ number_format($client->hours_to_bill, 2, ',', ' ') }} h</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Aucun client disponible.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
