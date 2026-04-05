@extends('layouts.app')

@section('content')
<div class="cadre">
    <form class="barre-filtres" method="GET" action="{{ route('projects.index') }}">
        <div class="filtre-groupe">
            <label for="recherche">Rechercher</label>
            <input type="text" id="recherche" name="q" value="{{ request('q') }}" placeholder="Nom projet ou client...">
        </div>
        <div class="filtre-groupe">
            <label for="statut">Statut</label>
            <select id="statut" name="status">
                <option value="">Tous</option>
                @foreach (['Actif', 'En cours', 'Planifie', 'Termine'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="filtre-groupe">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id">
                <option value="">Tous les clients</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected((string) request('client_id') === (string) $client->id)>{{ $client->name }}</option>
                @endforeach
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
            <th>Temps passe</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($projects as $project)
            <tr>
                <td>#{{ $project->id }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->client?->name ?? 'N/A' }}</td>
                <td>{{ $project->status }}</td>
                <td>{{ number_format((float) ($project->hours_spent ?? 0), 2, ',', ' ') }} h</td>
                <td>
                    <a href="{{ route('projects.show', ['id' => $project->id]) }}">Detail</a>
                    |
                    <form method="POST" action="{{ route('projects.destroy', ['id' => $project->id]) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce projet et ses tickets ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer;padding:0;">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Aucun projet disponible.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<section class="section-stats section-actions-bas">
    <a href="{{ route('projects.create') }}" class="carte-stat carte-action">
        <h4>Nouveau projet</h4>
        <p>+</p>
    </a>
</section>
@endsection
