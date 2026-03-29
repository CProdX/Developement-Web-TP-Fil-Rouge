@extends('layouts.app')

@section('content')
<div class="cadre">
    <table class="tableau-liste">
        <thead>
        <tr>
            <th>ID</th>
            <th>Projet</th>
            <th>Client</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($projects as $project)
            @php($client = collect($clients)->firstWhere('id', $project['client_id']))
            <tr>
                <td>#{{ $project['id'] }}</td>
                <td>{{ $project['name'] }}</td>
                <td>{{ $client['name'] ?? 'N/A' }}</td>
                <td>{{ $project['status'] }}</td>
                <td>
                    <a href="{{ route('projects.show', ['id' => $project['id']]) }}">Detail</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucun projet disponible.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
