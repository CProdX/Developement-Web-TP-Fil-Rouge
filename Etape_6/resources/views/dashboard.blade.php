@extends('layouts.app')

@section('content')
<section class="section-stats">
    <div class="carte-stat">
        <h4>Projets actifs</h4>
        <p>{{ $stats['projects'] }}</p>
    </div>
    <div class="carte-stat">
        <h4>Tickets crees</h4>
        <p>{{ $stats['tickets'] }}</p>
    </div>
    <div class="carte-stat">
        <h4>Tickets inclus</h4>
        <p>{{ $stats['included'] }}</p>
    </div>
    <div class="carte-stat">
        <h4>Tickets facturables</h4>
        <p>{{ $stats['billable'] }}</p>
    </div>
</section>

<div class="cadre">
    <h3 class="titre-formulaire">Analyses Globales</h3>
    <div class="conteneur-graphiques">
        <div class="boite-graphique">
            <h4>Par Statut</h4>
            <img src="{{ asset('Image2.png') }}" alt="Graphique Statut" class="img-graphique">
        </div>
        <div class="boite-graphique">
            <h4>Par Priorite</h4>
            <img src="{{ asset('Image1.png') }}" alt="Graphique Priorite" class="img-graphique">
        </div>
    </div>
</div>

<section class="section-stats section-actions-bas">
    <a href="{{ route('projects.create') }}" class="carte-stat carte-action">
        <h4>Nouveau projet</h4>
        <p>+</p>
    </a>
    <a href="{{ route('tickets.create') }}" class="carte-stat carte-action">
        <h4>Nouveau ticket</h4>
        <p>+</p>
    </a>
</section>
@endsection
