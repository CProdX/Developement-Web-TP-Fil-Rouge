@php
$activePage = match (true) {
    request()->routeIs('dashboard') => 'dashboard',
    request()->routeIs('projects.*') => 'projects',
    request()->routeIs('tickets.*') => 'tickets',
    request()->routeIs('clients.*') => 'clients',
    request()->routeIs('profile.*') => 'profile',
    request()->routeIs('settings.*') => 'settings',
    default => '',
};

$authOnlyNav = request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('password.request');
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ESIEA TICKETING' }}</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}">
    <script src="{{ asset('validation.js') }}" defer></script>
</head>
<body data-active-page="{{ $activePage }}">
<header>
    <div class="conteneur-header">
        <h1>{{ $heading ?? 'ESIEA TICKETING' }}</h1>
        <nav>
            @if ($authOnlyNav)
                <a href="{{ route('login') }}" aria-current="page">Connexion</a>
            @else
                <a href="{{ route('dashboard') }}" @if($activePage === 'dashboard') aria-current="page" @endif>Tableau de bord</a>
                <a href="{{ route('projects.index') }}" @if($activePage === 'projects') aria-current="page" @endif>Projets</a>
                <a id="nav-new-project" href="{{ route('projects.create') }}" style="display:none;">+ Nouveau projet</a>
                <a href="{{ route('tickets.index') }}" @if($activePage === 'tickets') aria-current="page" @endif>Tickets</a>
                <a id="nav-new-ticket" href="{{ route('tickets.create') }}" style="display:none;">+ Nouveau ticket</a>
                <a href="{{ route('clients.index') }}" @if($activePage === 'clients') aria-current="page" @endif>Clients</a>
                <a href="{{ route('profile.show') }}" @if($activePage === 'profile') aria-current="page" @endif>Profil</a>
                <a href="{{ route('settings.show') }}" @if($activePage === 'settings') aria-current="page" @endif>Parametres</a>
                <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                    @csrf
                    <button type="submit" class="nav-logout-button Deconnexion">Deconnexion</button>
                </form>
            @endif
        </nav>
    </div>
</header>

<main class="@yield('main_class')">
    @if (session('success'))
        <div class="message-alerte message-success message-bas-centre">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="message-alerte message-error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="message-alerte message-error">
            <strong>Formulaire invalide :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <p>&copy; 2026 Projet TP FIL ROUGE - Collou Christian-Didier KOUAKOU | ESIEA</p>
</footer>
</body>
</html>

