@extends('layouts.app')

@section('content')
<div class="cadre">
    <form class="barre-filtres" method="GET" action="{{ route('projects.index') }}">
        <div class="filtre-groupe">
            <label for="recherche">Rechercher</label>
            <input type="text" id="recherche" name="q" value="{{ $q ?? '' }}" placeholder="Nom projet ou client...">
        </div>
        <div class="filtre-groupe">
            <label for="statut">Statut</label>
            <select id="statut" name="statut">
                <option value="tous" {{ ($statut ?? 'tous') === 'tous' ? 'selected' : '' }}>Tous</option>
                <option value="Actif" {{ ($statut ?? 'tous') === 'Actif' ? 'selected' : '' }}>Actif</option>
                <option value="En attente client" {{ ($statut ?? 'tous') === 'En attente client' ? 'selected' : '' }}>En attente client</option>
            </select>
        </div>
        <button type="submit" class="bouton-rechercher">Filtrer</button>
    </form>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Projet</th>
            <th>Client</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($projects as $project)
            @php($client = collect($clients)->firstWhere('id', $project['client_id']))
            <tr>
                <td>#{{ $project['id'] }}</td>
                <td>{{ $project['name'] }}</td>
                <td>{{ $client['name'] ?? 'N/A' }}</td>
                <td>{{ $project['status'] }}</td>
                <td>
                    <a href="{{ route('projects.show', ['id' => $project['id']]) }}">Detail</a>
                    |
                    <form method="POST" action="{{ route('projects.destroy', ['id' => $project['id']]) }}" style="display:inline;" onsubmit="return confirm('Etes-vous sur de vouloir supprimer ce projet ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color:#dc2626; background:none; border:none; cursor:pointer; padding:0; text-decoration:underline; font-size:inherit;">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucun projet ne correspond a votre recherche.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
