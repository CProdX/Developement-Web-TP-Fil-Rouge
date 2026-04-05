@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">PREFERENCES</h2>

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <div class="champ-formulaire">
            <label for="lang">Langue</label>
            <select id="lang" name="lang" required>
                <option value="fr">Francais</option>
                <option value="en">English</option>
            </select>
        </div>

        <div class="champ-formulaire">
            <label for="notif">Notifications</label>
            <select id="notif" name="notif" required>
                <option value="oui">Activees</option>
                <option value="non">Desactivees</option>
            </select>
        </div>

        <button type="submit" class="bouton-large">Enregistrer</button>
    </form>
</div>
@endsection
