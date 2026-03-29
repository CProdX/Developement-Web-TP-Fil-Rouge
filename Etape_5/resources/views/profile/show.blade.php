@extends('layouts.app')

@section('content')
<div class="cadre largeur-formulaire">
    <h2 class="titre-formulaire">MON PROFIL</h2>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="champ-formulaire">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ $user['name'] }}" required>
        </div>

        <div class="champ-formulaire">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user['email'] }}" required>
        </div>

        <button type="submit" class="bouton-large">Mettre a jour</button>
    </form>
</div>
@endsection
