@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">NOUVEAU PROJET</h2>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div class="champ-formulaire">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="champ-formulaire">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id" required>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected((int) old('client_id') === $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="contract_id">Contrat (optionnel)</label>
            <select id="contract_id" name="contract_id">
                <option value="">Aucun</option>
                @foreach ($contracts as $contract)
                    <option value="{{ $contract->id }}" @selected((int) old('contract_id') === $contract->id)>
                        {{ $contract->name }} - {{ $contract->client?->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="status">Statut</label>
            <select id="status" name="status" required>
                <option value="Planifie" @selected(old('status') === 'Planifie')>Planifie</option>
                <option value="En cours" @selected(old('status', 'En cours') === 'En cours')>En cours</option>
                <option value="Termine" @selected(old('status') === 'Termine')>Termine</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="bouton-large">Creer</button>
    </form>
</div>
@endsection
