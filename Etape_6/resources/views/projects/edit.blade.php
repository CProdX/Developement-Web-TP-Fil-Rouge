@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">MODIFIER PROJET #{{ $project->id }}</h2>

    <form method="POST" action="{{ route('projects.update', ['id' => $project->id]) }}">
        @csrf
        @method('PUT')

        <div class="champ-formulaire">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required>
        </div>

        <div class="champ-formulaire">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id" required>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected((string) old('client_id', $project->client_id) === (string) $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="contract_id">Contrat (optionnel)</label>
            <select id="contract_id" name="contract_id">
                <option value="">Aucun</option>
                @foreach ($contracts as $contract)
                    <option value="{{ $contract->id }}" @selected((string) old('contract_id', $project->contract_id) === (string) $contract->id)>
                        {{ $contract->name }} - {{ $contract->client?->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="status">Statut</label>
            <select id="status" name="status" required>
                @foreach (['Planifie', 'En cours', 'Termine'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $project->status) === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
        </div>

        <button type="submit" class="bouton-large">Enregistrer</button>
    </form>
</div>
@endsection

