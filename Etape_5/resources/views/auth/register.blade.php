@extends('layouts.app')

@section('main_class', 'centrer-flex')

@section('content')
<div class="cadre largeur-login">
    <h2 class="titre-formulaire">INSCRIPTION</h2>

    <form method="POST" action="{{ route('register.submit') }}">
        @csrf
        <div class="champ-formulaire">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="champ-formulaire">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="nom@et.esiea.fr" required>
        </div>

        <div class="champ-formulaire">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="bouton-large">S'inscrire</button>
    </form>
</div>
@endsection
