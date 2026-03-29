@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">TEMPS PASSE</h2>

    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Ticket</th>
            <th>Collaborateur</th>
            <th>Heures</th>
            <th>Note</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($entries as $entry)
            @php($ticket = collect($tickets)->firstWhere('id', $entry['ticket_id']))
            @php($user = collect($users)->firstWhere('id', $entry['user_id']))
            <tr>
                <td>#{{ $entry['id'] }}</td>
                <td>#{{ $entry['ticket_id'] }} - {{ $ticket['title'] ?? 'N/A' }}</td>
                <td>{{ $user['name'] ?? 'N/A' }}</td>
                <td>{{ $entry['hours'] }} h</td>
                <td>{{ $entry['note'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

