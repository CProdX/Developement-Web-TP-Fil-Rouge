<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$statut = isset($_GET['statut']) ? trim((string) $_GET['statut']) : 'tous';

$projects = $_SESSION['projects'];
$tickets = $_SESSION['tickets'];
$filteredProjects = [];
$success = getFlash('success');
$error = getFlash('error');

foreach ($projects as $project) {
    $label = projectLabel($project);

    $matchSearch = $q === ''
        || stripos($label, $q) !== false
        || stripos((string) $project['client'], $q) !== false;

    $matchStatut = $statut === 'tous' || strtolower((string) $project['statut']) === strtolower($statut);

    if ($matchSearch && $matchStatut) {
        $filteredProjects[] = $project;
    }
}

renderHeader('Projets - Etape 3 PHP', 'GESTION DES PROJETS', 'projects');
?>
<main>
    <div class="cadre">
        <?php if ($success): ?>
            <div class="message-alerte message-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="barre-filtres" method="get" action="<?= e(url('projects.php')) ?>">
            <div class="filtre-groupe">
                <label for="recherche">Rechercher</label>
                <input type="text" id="recherche" name="q" value="<?= e($q) ?>" placeholder="Nom projet ou client...">
            </div>
            <div class="filtre-groupe">
                <label for="statut">Statut</label>
                <select id="statut" name="statut">
                    <option value="tous" <?= $statut === 'tous' ? 'selected' : '' ?>>Tous</option>
                    <option value="Actif" <?= $statut === 'Actif' ? 'selected' : '' ?>>Actif</option>
                    <option value="En attente client" <?= $statut === 'En attente client' ? 'selected' : '' ?>>En attente client</option>
                </select>
            </div>
            <button type="submit" class="bouton-rechercher">Filtrer</button>
        </form>

        <table class="tableau-liste">
            <thead>
            <tr>
                <th>ID</th>
                <th>Projet</th>
                <th>Contrat</th>
                <th>Consomme</th>
                <th>Tickets</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($filteredProjects === []): ?>
                <tr>
                    <td colspan="7">Aucun projet ne correspond a votre recherche.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($filteredProjects as $project): ?>
                    <?php
                    $ticketCount = 0;
                    foreach ($tickets as $ticket) {
                        if ((int) $ticket['project_id'] === (int) $project['id']) {
                            $ticketCount++;
                        }
                    }
                    ?>
                    <tr>
                        <td>#<?= e((string) $project['id']) ?></td>
                        <td><?= e(projectLabel($project)) ?></td>
                        <td><?= e((string) $project['heures_contrat']) ?>h</td>
                        <td><?= e((string) $project['heures_consommees']) ?>h</td>
                        <td><?= e((string) $ticketCount) ?></td>
                        <td><?= e((string) $project['statut']) ?></td>
                        <td><a href="<?= e(url('project-detail.php?id=' . (int) $project['id'])) ?>">Detail</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php renderFooter();

