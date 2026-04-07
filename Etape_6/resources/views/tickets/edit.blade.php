@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">EDITER TICKET #{{ $ticket->id }}</h2>

    <form method="POST" action="{{ route('tickets.update', ['id' => $ticket->id]) }}">
        @csrf
        @method('PUT')

        <div class="champ-formulaire">
            <label for="title">Sujet</label>
            <input type="text" id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
        </div>

        <div class="champ-formulaire">
            <label for="project_id">Projet</label>
            <select id="project_id" name="project_id" required>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((int) old('project_id', $ticket->project_id) === $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="status">Statut</label>
            <select id="status" name="status" required>
                <option value="Ouvert" @selected(old('status', $ticket->status) === 'Ouvert')>Ouvert</option>
                <option value="En cours" @selected(old('status', $ticket->status) === 'En cours')>En cours</option>
                <option value="Ferme" @selected(old('status', $ticket->status) === 'Ferme')>Ferme</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="priority">Priorite</label>
            @php($priorityValue = old('priority', $ticket->priority))
            <select id="priority" name="priority" required>
                <option value="Basse" @selected($priorityValue === 'Basse')>Basse</option>
                <option value="Moyenne" @selected($priorityValue === 'Moyenne')>Moyenne</option>
                <option value="Haute" @selected($priorityValue === 'Haute')>Haute</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="billing_type">Type de facturation</label>
            <select id="billing_type" name="billing_type" required>
                <option value="inclus" @selected(old('billing_type', $ticket->billing_type) === 'inclus')>Inclus</option>
                <option value="facturable" @selected(old('billing_type', $ticket->billing_type) === 'facturable')>Facturable</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $ticket->description) }}</textarea>
        </div>

        <button type="submit" class="bouton-large">Enregistrer</button>
    </form>
</div>
@endsection

