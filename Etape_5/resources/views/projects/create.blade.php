@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">NOUVEAU PROJET</h2>

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div class="champ-formulaire">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="champ-formulaire">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id" required>
                @foreach ($clients as $client)
                    <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="status">Statut</label>
            <select id="status" name="status" required>
                <option value="Planifie">Planifie</option>
                <option value="En cours">En cours</option>
                <option value="Termine">Termine</option>
            </select>
        </div>

        <button type="submit" class="bouton-large">Creer</button>
    </form>
</div>
@endsection
