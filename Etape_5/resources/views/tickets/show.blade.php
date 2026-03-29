@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">DETAIL TICKET #{{ $ticket['id'] }}</h2>

    <div class="info-metier">
        <p><strong>Sujet:</strong> {{ $ticket['title'] }}</p>
        <p><strong>Projet:</strong> {{ $project['name'] ?? 'N/A' }}</p>
        <p><strong>Statut:</strong> {{ $ticket['status'] }}</p>
        <p><strong>Priorite:</strong> {{ $ticket['priority'] }}</p>
        <p><strong>Type:</strong> {{ ucfirst($ticket['billing_type']) }}</p>
        <p><strong>Temps passe:</strong> {{ $ticket['hours_spent'] }} h</p>
    </div>

    <div class="champ-formulaire">
        <label>Description</label>
        <textarea rows="5" readonly>{{ $ticket['description'] }}</textarea>
    </div>

    <a href="{{ route('tickets.edit', ['id' => $ticket['id']]) }}">Modifier ce ticket</a>
</div>
@endsection
