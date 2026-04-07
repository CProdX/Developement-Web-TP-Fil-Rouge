@extends('layouts.app')

@section('content')
<div class="cadre">
    <form class="barre-filtres" method="GET" action="{{ route('tickets.index') }}">
        <div class="filtre-groupe">
            <label for="recherche">Rechercher</label>
            <input type="text" id="recherche" name="q" value="{{ $q ?? '' }}" placeholder="ID, sujet ou projet...">
        </div>
        <div class="filtre-groupe">
            <label for="type">Type</label>
            <select id="type" name="type">
                <option value="tous" {{ ($type ?? 'tous') === 'tous' ? 'selected' : '' }}>Tous</option>
                <option value="Inclus" {{ ($type ?? 'tous') === 'Inclus' ? 'selected' : '' }}>Inclus</option>
                <option value="Facturable" {{ ($type ?? 'tous') === 'Facturable' ? 'selected' : '' }}>Facturable</option>
            </select>
        </div>
        <div class="filtre-groupe">
            <label for="project_id">Projet</label>
            <select id="project_id" name="project_id">
                <option value="0">Tous les projets</option>
                @foreach ($projects as $project)
                    <option value="{{ $project['id'] }}" {{ (int) ($projectFilter ?? 0) === (int) $project['id'] ? 'selected' : '' }}>
                        {{ $project['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bouton-rechercher">Filtrer</button>
    </form>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Sujet</th>
            <th>Projet</th>
            <th>Type</th>
            <th>Priorite</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($tickets as $ticket)
            @php($project = collect($projects)->firstWhere('id', $ticket['project_id']))
            <tr>
                <td>#{{ $ticket['id'] }}</td>
                <td>{{ $ticket['title'] }}</td>
                <td>{{ $project['name'] ?? 'N/A' }}</td>
                <td>{{ ucfirst($ticket['billing_type']) }}</td>
                <td>{{ $ticket['priority'] }}</td>
                <td>{{ $ticket['status'] }}</td>
                <td>
                    <a href="{{ route('tickets.show', ['id' => $ticket['id']]) }}">Detail</a>
                    |
                    <a href="{{ route('tickets.edit', ['id' => $ticket['id']]) }}">Modifier</a>
                    |
                    <form method="POST" action="{{ route('tickets.destroy', ['id' => $ticket['id']]) }}" style="display:inline;" onsubmit="return confirm('Etes-vous sur de vouloir supprimer ce ticket ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color:#dc2626; background:none; border:none; cursor:pointer; padding:0; text-decoration:underline; font-size:inherit;">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Aucun ticket ne correspond a votre recherche.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
