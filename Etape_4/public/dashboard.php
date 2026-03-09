<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$tickets = ticketFindAll();
$projects = projectFindAll();
$resolus = 0;
$aValider = 0;

foreach ($tickets as $ticket) {
    if ($ticket['statut'] === 'Valide' || $ticket['statut'] === 'Termine') {
        $resolus++;
    }
    if ($ticket['statut'] === 'A valider') {
        $aValider++;
    }
}

$stats = [
    'Projets actifs' => count($projects),
    'Tickets crees' => count($tickets),
    'Tickets resolus' => $resolus,
    'A valider' => $aValider,
];

$success = getFlash('success');
$error = getFlash('error');

renderHeader('Dashboard - Etape 4 PHP', 'TABLEAU DE BORD', 'dashboard');
?>
<main>
    <?php if ($success): ?>
        <div class="message-alerte message-success message-bas-centre"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message-alerte message-error"><?= e($error) ?></div>
    <?php endif; ?>

    <section class="section-stats">
        <?php foreach ($stats as $label => $value): ?>
            <div class="carte-stat">
                <h4><?= e($label) ?></h4>
                <p><?= e((string) $value) ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <div class="cadre">
        <h3 class="titre-formulaire">Analyses Globales</h3>
        <div class="conteneur-graphiques">
            <div class="boite-graphique">
                <h4>Par Statut</h4>
                <img src="Image2.png" alt="Graphique Statut" class="img-graphique">
            </div>
            <div class="boite-graphique">
                <h4>Par Priorite</h4>
                <img src="Image1.png" alt="Graphique Priorite" class="img-graphique">
            </div>
        </div>
    </div>
</main>
<?php renderFooter();
