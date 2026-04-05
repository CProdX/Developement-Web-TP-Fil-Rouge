@extends('layouts.app')

@section('main_class', 'centrer-flex')

@section('content')
<div class="cadre largeur-login">
    <h2 class="titre-formulaire">IDENTIFICATION</h2>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="champ-formulaire">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="nom@et.esiea.fr" required>
        </div>
        <div class="champ-formulaire">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="bouton-large">Se connecter</button>
    </form>

    <p style="margin-top: 12px;">
        <a href="{{ route('password.request') }}">Mot de passe oublie?</a>
        |
        <a href="{{ route('register') }}">S'inscrire</a>
    </p>
</div>
@endsection
