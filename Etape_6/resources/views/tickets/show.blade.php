@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">DETAIL TICKET #{{ $ticket->id }}</h2>

    <div class="info-metier">
        <p><strong>Sujet:</strong> {{ $ticket->title }}</p>
        <p><strong>Projet:</strong> {{ $project?->name ?? 'N/A' }}</p>
        <p><strong>Statut:</strong> {{ $ticket->status }}</p>
        <p><strong>Priorite:</strong> {{ $ticket->priority === 'Critique' ? 'Haute' : $ticket->priority }}</p>
        <p><strong>Type:</strong> {{ ucfirst($ticket->billing_type) }}</p>
        <p><strong>Temps passe:</strong> {{ number_format($ticket->hours_spent, 2, ',', ' ') }} h</p>
    </div>

    <div class="champ-formulaire">
        <label>Description</label>
        <textarea rows="5" readonly>{{ $ticket->description }}</textarea>
    </div>

    <h3>Ajouter du temps</h3>
    <form method="POST" action="{{ route('tickets.time-entries.store', ['ticketId' => $ticket->id]) }}" class="largeur-formulaire">
        @csrf
        <div class="champ-formulaire">
            <label for="user_id">Collaborateur</label>
            <select id="user_id" name="user_id">
                <option value="">Non assigne</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="champ-formulaire">
            <label for="hours">Heures</label>
            <input type="number" step="0.25" min="0.25" max="24" id="hours" name="hours" required>
        </div>
        <div class="champ-formulaire">
            <label for="note">Note</label>
            <input type="text" id="note" name="note" maxlength="255">
        </div>
        <button type="submit" class="bouton-large">Ajouter</button>
    </form>

    <h3>Historique du temps passe</h3>
    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Collaborateur</th>
            <th>Heures</th>
            <th>Note</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($ticket->timeEntries as $entry)
            <tr>
                <td>#{{ $entry->id }}</td>
                <td>{{ $entry->user?->name ?? 'N/A' }}</td>
                <td>{{ number_format($entry->hours, 2, ',', ' ') }} h</td>
                <td>{{ $entry->note }}</td>
                <td>
                    <form method="POST" action="{{ route('time-entries.destroy', ['id' => $entry->id]) }}" onsubmit="return confirm('Supprimer cette saisie de temps ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucune saisie de temps.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <p style="margin-top:16px;">
        <a href="{{ route('tickets.edit', ['id' => $ticket->id]) }}">Modifier ce ticket</a>
    </p>
</div>
@endsection
