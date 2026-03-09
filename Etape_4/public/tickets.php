<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$type = isset($_GET['type']) ? trim((string) $_GET['type']) : 'tous';
$projectFilter = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;

$projects = projectFindAll();
$tickets = ticketFindAll();
$filteredTickets = [];

foreach ($tickets as $ticket) {
    $projectLabelText = ticketProjectLabel($ticket, $projects);

    $matchSearch = $q === ''
        || stripos((string) $ticket['sujet'], $q) !== false
        || stripos((string) $ticket['id'], $q) !== false
        || stripos($projectLabelText, $q) !== false;

    $matchType = $type === 'tous' || strtolower((string) $ticket['type']) === strtolower($type);
    $matchProject = $projectFilter === 0 || (int) $ticket['project_id'] === $projectFilter;

    if ($matchSearch && $matchType && $matchProject) {
        $filteredTickets[] = $ticket;
    }
}

$success = getFlash('success');
$error = getFlash('error');

renderHeader('Tickets - Etape 4 PHP', 'GESTION DES TICKETS', 'tickets');
?>
<main>
    <div class="cadre">
        <?php if ($success): ?>
            <div class="message-alerte message-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form class="barre-filtres" method="get" action="<?= e(url('tickets.php')) ?>">
            <div class="filtre-groupe">
                <label for="recherche">Rechercher</label>
                <input type="text" id="recherche" name="q" value="<?= e($q) ?>" placeholder="ID, sujet ou projet...">
            </div>
            <div class="filtre-groupe">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="tous" <?= $type === 'tous' ? 'selected' : '' ?>>Tous</option>
                    <option value="Inclus" <?= $type === 'Inclus' ? 'selected' : '' ?>>Inclus</option>
                    <option value="Facturable" <?= $type === 'Facturable' ? 'selected' : '' ?>>Facturable</option>
                </select>
            </div>
            <div class="filtre-groupe">
                <label for="project_id">Projet</label>
                <select id="project_id" name="project_id">
                    <option value="0">Tous les projets</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?= e((string) $project['id']) ?>" <?= $projectFilter === (int) $project['id'] ? 'selected' : '' ?>>
                            <?= e(projectLabel($project)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bouton-rechercher">Filtrer</button>
        </form>

        <table class="tableau-liste">
            <thead>
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Projet</th>
                <th>Type</th>
                <th>Temps</th>
                <th>Priorite</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($filteredTickets === []): ?>
                <tr>
                    <td colspan="8">Aucun ticket ne correspond a votre recherche.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($filteredTickets as $ticket): ?>
                    <tr>
                        <td>#<?= e((string) $ticket['id']) ?></td>
                        <td><?= e((string) $ticket['sujet']) ?></td>
                        <td><?= e(ticketProjectLabel($ticket, $projects)) ?></td>
                        <td><?= e((string) $ticket['type']) ?></td>
                        <td><?= e((string) $ticket['temps']) ?></td>
                        <td><?= e((string) $ticket['priorite']) ?></td>
                        <td><?= e((string) $ticket['statut']) ?></td>
                        <td>
                            <a href="<?= e(url('ticket-detail.php?id=' . (int) $ticket['id'])) ?>">Detail</a>
                            |
                            <a href="<?= e(url('ticket-edit.php?id=' . (int) $ticket['id'])) ?>">Modifier</a>
                            |
                            <form method="post" action="<?= e(url('delete_ticket_action.php')) ?>" style="display: inline;" onsubmit="return confirm('Etes-vous sur de vouloir supprimer ce ticket ?');">
                                <input type="hidden" name="id" value="<?= e((string) $ticket['id']) ?>">
                                <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline; padding: 0; font-size: inherit;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php renderFooter();

