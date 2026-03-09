<?php

function renderHeader($title, $heading, $activePage = '')
{
    $user = currentUser();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= e($title) ?></title>
        <link rel="stylesheet" href="<?= e(url('styles.css')) ?>">
        <script src="<?= e(url('validation.js')) ?>" defer></script>
    </head>
    <body data-active-page="<?= e($activePage) ?>">
    <header>
        <div class="conteneur-header">
            <h1><?= e($heading) ?></h1>
            <nav>
                <?php if ($user): ?>
                    <a href="<?= e(url('dashboard.php')) ?>" <?= $activePage === 'dashboard' ? 'aria-current="page"' : '' ?>>Tableau de bord</a>
                    <a href="<?= e(url('projects.php')) ?>" <?= $activePage === 'projects' ? 'aria-current="page"' : '' ?>>Projets</a>
                    <a id="nav-new-project" href="<?= e(url('project-create.php')) ?>" style="display:none;">+ Nouveau projet</a>
                    <a href="<?= e(url('tickets.php')) ?>" <?= $activePage === 'tickets' ? 'aria-current="page"' : '' ?>>Tickets</a>
                    <a id="nav-new-ticket" href="<?= e(url('ticket-create.php')) ?>" style="display:none;">+ Nouveau ticket</a>
                    <a href="<?= e(url('profile.php')) ?>" <?= $activePage === 'profile' ? 'aria-current="page"' : '' ?>>Profil</a>
                    <a href="<?= e(url('settings.php')) ?>" <?= $activePage === 'settings' ? 'aria-current="page"' : '' ?>>Parametres</a>
                    <a href="<?= e(url('actions/auth/logout.php')) ?>" class="Deconnexion">Deconnexion</a>
                <?php else: ?>
                    <a href="<?= e(url('index.php')) ?>" aria-current="page">Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <?php
}

function renderFooter()
{
    ?>
    <footer>
        <p>&copy; 2026 Projet TP FIL ROUGE - Collou Christian-Didier KOUAKOU | ESIEA</p>
    </footer>
    </body>
    </html>
    <?php
}
