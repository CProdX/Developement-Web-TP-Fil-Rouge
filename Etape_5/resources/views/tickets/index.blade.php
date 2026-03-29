@extends('layouts.app')

@section('content')
<div class="cadre">
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
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Aucun ticket disponible.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

