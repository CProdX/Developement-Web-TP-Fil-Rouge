@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">DETAIL PROJET #{{ $project['id'] }}</h2>

    <div class="info-metier">
        <p><strong>Nom:</strong> {{ $project['name'] }}</p>
        <p><strong>Client:</strong> {{ $client['name'] ?? 'N/A' }}</p>
        <p><strong>Statut:</strong> {{ $project['status'] }}</p>
    </div>

    <h3>Tickets lies</h3>
    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Sujet</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($tickets as $ticket)
            <tr>
                <td>#{{ $ticket['id'] }}</td>
                <td>{{ $ticket['title'] }}</td>
                <td><a href="{{ route('tickets.show', ['id' => $ticket['id']]) }}">Detail</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Aucun ticket pour ce projet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

