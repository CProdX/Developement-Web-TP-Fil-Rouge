@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">NOUVEAU TICKET</h2>

    <form method="POST" action="{{ route('tickets.store') }}">
        @csrf
        <div class="champ-formulaire">
            <label for="title">Sujet</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="champ-formulaire">
            <label for="project_id">Projet</label>
            <select id="project_id" name="project_id" required>
                @foreach ($projects as $project)
                    <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="priority">Priorite</label>
            <select id="priority" name="priority" required>
                <option value="Basse">Basse</option>
                <option value="Moyenne">Moyenne</option>
                <option value="Haute">Haute</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="billing_type">Type de facturation</label>
            <select id="billing_type" name="billing_type" required>
                <option value="inclus">Inclus</option>
                <option value="facturable">Facturable</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>

        <button type="submit" class="bouton-large">Creer</button>
    </form>
</div>
@endsection
