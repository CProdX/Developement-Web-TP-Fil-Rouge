@extends('layouts.app')

@section('content')
<div class="cadre">
    <h2 class="titre-formulaire">DETAIL PROJET #{{ $project->id }}</h2>

    <div class="info-metier">
        <p><strong>Nom:</strong> {{ $project->name }}</p>
        <p><strong>Client:</strong> {{ $client?->name ?? 'N/A' }}</p>
        <p><strong>Statut:</strong> {{ $project->status }}</p>
    </div>

    <h3>Suivi du temps (calcule automatiquement)</h3>
    <p style="margin-top:-8px; color:#475569;">Le cumul prend en compte uniquement les tickets au statut "En cours" ou "Ferme".</p>
    <div class="info-metier">
        <p><strong>Total:</strong> {{ number_format((float) ($project->hours_spent ?? 0), 2, ',', ' ') }} h</p>
        <p><strong>Inclus:</strong> {{ number_format((float) ($project->included_hours_spent ?? 0), 2, ',', ' ') }} h</p>
        <p><strong>Facturable:</strong> {{ number_format((float) ($project->billable_hours_spent ?? 0), 2, ',', ' ') }} h</p>
    </div>

    <h3>Tickets lies</h3>
    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Sujet</th>
            <th>Temps passe</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ number_format($ticket->hours_spent, 2, ',', ' ') }} h</td>
                <td><a href="{{ route('tickets.show', ['id' => $ticket->id]) }}">Detail</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Aucun ticket pour ce projet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
