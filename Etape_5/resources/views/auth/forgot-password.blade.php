@extends('layouts.app')

@section('main_class', 'centrer-flex')

@section('content')
<div class="cadre largeur-login">
    <h2 class="titre-formulaire">MOT DE PASSE OUBLIE</h2>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="champ-formulaire">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="nom@et.esiea.fr" required>
        </div>
        <button type="submit" class="bouton-large">Envoyer</button>
    </form>
</div>
@endsection
