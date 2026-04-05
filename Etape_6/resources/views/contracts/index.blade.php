@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">CONTRATS</h2>

    <form method="GET" action="{{ route('contracts.index') }}" class="largeur-formulaire" style="margin-bottom:20px;">
        <h3 class="titre-formulaire" style="font-size:1.1rem;">FILTRES</h3>

        <div class="champ-formulaire">
            <label for="q">Recherche</label>
            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Nom du contrat">
        </div>

        <div class="champ-formulaire">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id">
                <option value="">Tous</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected((string) request('client_id') === (string) $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">
            <button type="submit" class="bouton-large">Filtrer</button>
            <a href="{{ route('contracts.index') }}" class="bouton-large" style="text-align:center;">Reinitialiser</a>
        </div>
    </form>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Client</th>
            <th>Heures incluses</th>
            <th>Utilisees</th>
            <th>Restantes</th>
            <th>A facturer</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($contracts as $contract)
            <tr>
                <td>#{{ $contract->id }}</td>
                <td>{{ $contract->name }}</td>
                <td>{{ $contract->client?->name ?? 'N/A' }}</td>
                <td>{{ number_format($contract->included_hours, 2, ',', ' ') }} h</td>
                <td>{{ number_format($contract->hours_used, 2, ',', ' ') }} h</td>
                <td>{{ number_format($contract->hours_remaining, 2, ',', ' ') }} h</td>
                <td>{{ number_format($contract->hours_to_bill, 2, ',', ' ') }} h</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Aucun contrat disponible.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

