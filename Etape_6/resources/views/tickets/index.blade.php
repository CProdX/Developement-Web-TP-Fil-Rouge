@extends('layouts.app')

@section('content')
<div class="cadre">
    <form class="barre-filtres" method="GET" action="{{ route('tickets.index') }}">
        <div class="filtre-groupe">
            <label for="recherche">Rechercher</label>
            <input type="text" id="recherche" name="q" value="{{ request('q') }}" placeholder="ID, sujet ou projet...">
        </div>
        <div class="filtre-groupe">
            <label for="type">Type</label>
            <select id="type" name="billing_type">
                <option value="">Tous</option>
                <option value="inclus" @selected(request('billing_type') === 'inclus')>Inclus</option>
                <option value="facturable" @selected(request('billing_type') === 'facturable')>Facturable</option>
            </select>
        </div>
        <div class="filtre-groupe">
            <label for="project_id">Projet</label>
            <select id="project_id" name="project_id">
                <option value="">Tous les projets</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((string) request('project_id') === (string) $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filtre-groupe">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="">Tous</option>
                @foreach (['Ouvert', 'En cours', 'Ferme'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="filtre-groupe">
            <label for="priority">Priorite</label>
            <select id="priority" name="priority">
                <option value="">Toutes</option>
                @foreach (['Basse', 'Moyenne', 'Haute'] as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
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
            <th>Temps passe</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->project?->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($ticket->billing_type) }}</td>
                <td>{{ $ticket->priority }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ number_format($ticket->hours_spent, 2, ',', ' ') }} h</td>
                <td>
                    <div>
                        <a href="{{ route('tickets.show', ['id' => $ticket->id]) }}">Detail</a>
                        |
                        <a href="{{ route('tickets.edit', ['id' => $ticket->id]) }}">Modifier</a>
                    </div>
                    <form method="POST" action="{{ route('tickets.destroy', ['id' => $ticket->id]) }}" style="margin-top:6px;" onsubmit="return confirm('Supprimer ce ticket ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer;padding:0;">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Aucun ticket disponible.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<section class="section-stats section-actions-bas">
    <a href="{{ route('tickets.create') }}" class="carte-stat carte-action">
        <h4>Nouveau ticket</h4>
        <p>+</p>
    </a>
</section>
@endsection

