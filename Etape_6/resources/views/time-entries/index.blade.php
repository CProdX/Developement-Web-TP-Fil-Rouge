@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">TEMPS PASSE</h2>

    <form class="barre-filtres" method="GET" action="{{ route('time-entries.index') }}">
        <div class="filtre-groupe">
            <label for="recherche">Rechercher</label>
            <input type="text" id="recherche" name="q" value="{{ request('q') }}" placeholder="Note...">
        </div>
        <div class="filtre-groupe">
            <label for="ticket_id">Ticket</label>
            <select id="ticket_id" name="ticket_id">
                <option value="">Tous les tickets</option>
                @foreach ($tickets as $ticket)
                    <option value="{{ $ticket->id }}" @selected((string) request('ticket_id') === (string) $ticket->id)>{{ $ticket->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="filtre-groupe">
            <label for="user_id">Collaborateur</label>
            <select id="user_id" name="user_id">
                <option value="">Tous les collaborateurs</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bouton-rechercher">Filtrer</button>
    </form>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Ticket</th>
            <th>Collaborateur</th>
            <th>Heures</th>
            <th>Note</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($entries as $entry)
            <tr>
                <td>#{{ $entry->id }}</td>
                <td>#{{ $entry->ticket_id }} - {{ $entry->ticket?->title ?? 'N/A' }}</td>
                <td>{{ $entry->user?->name ?? 'N/A' }}</td>
                <td>{{ number_format($entry->hours, 2, ',', ' ') }} h</td>
                <td>{{ $entry->note }}</td>
                <td>
                    <form method="POST" action="{{ route('time-entries.destroy', ['id' => $entry->id]) }}" onsubmit="return confirm('Supprimer cette saisie de temps ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#dc2626;cursor:pointer;padding:0;">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Aucune saisie de temps.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
