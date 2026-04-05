@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">MON PROFIL</h2>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="champ-formulaire">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="champ-formulaire">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <button type="submit" class="bouton-large">Mettre a jour</button>
    </form>

    <hr>

    <h3 class="titre-formulaire">MODIFIER LE MOT DE PASSE</h3>

    <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf
        @method('PUT')

        <div class="champ-formulaire">
            <label for="current_password">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="champ-formulaire">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" required minlength="8">
        </div>

        <div class="champ-formulaire">
            <label for="password_confirmation">Confirmation du nouveau mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
        </div>

        <button type="submit" class="bouton-large">Changer le mot de passe</button>
    </form>
</div>
@endsection
