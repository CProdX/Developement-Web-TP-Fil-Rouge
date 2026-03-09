<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$projectId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$project = projectFindById($projectId);

if ($project === null) {
    setFlash('error', 'Projet introuvable.');
    redirect('projects.php');
}

$projectTickets = [];
foreach (ticketFindAll() as $ticket) {
    if ((int) $ticket['project_id'] === (int) $project['id']) {
        $projectTickets[] = $ticket;
    }
}

$heuresRestantes = max(0, (float) $project['heures_contrat'] - (float) $project['heures_consommees']);

renderHeader('Detail projet - Etape 4 PHP', 'DETAIL PROJET', 'projects');
?>
<main>
    <div class="cadre">
        <h2 class="titre-formulaire"><?= e(projectLabel($project)) ?></h2>
        <p><?= e((string) $project['description']) ?></p>

        <div class="info-metier">
            <p><strong>Client:</strong> <?= e((string) $project['client']) ?></p>
            <p><strong>Statut:</strong> <?= e((string) $project['statut']) ?></p>
            <p><strong>Heures contrat:</strong> <?= e((string) $project['heures_contrat']) ?>h</p>
            <p><strong>Heures consommees:</strong> <?= e((string) $project['heures_consommees']) ?>h</p>
            <p><strong>Heures restantes:</strong> <?= e((string) $heuresRestantes) ?>h</p>
        </div>

        <h3>Tickets du projet</h3>
        <table class="tableau-liste">
            <thead>
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Type</th>
                <th>Priorite</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($projectTickets === []): ?>
                <tr><td colspan="6">Aucun ticket pour ce projet.</td></tr>
            <?php else: ?>
                <?php foreach ($projectTickets as $ticket): ?>
                    <tr>
                        <td>#<?= e((string) $ticket['id']) ?></td>
                        <td><?= e((string) $ticket['sujet']) ?></td>
                        <td><?= e((string) $ticket['type']) ?></td>
                        <td><?= e((string) $ticket['priorite']) ?></td>
                        <td><?= e((string) $ticket['statut']) ?></td>
                        <td><a href="<?= e(url('ticket-detail.php?id=' . (int) $ticket['id'])) ?>">Voir ticket</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <a href="<?= e(url('projects.php')) ?>">Retour aux projets</a>
            |
            <form method="post" action="<?= e(url('delete_project_action.php')) ?>" style="display: inline;" onsubmit="return confirm('Etes-vous sur de vouloir supprimer ce projet et tous ses tickets ?');">
                <input type="hidden" name="id" value="<?= e((string) $project['id']) ?>">
                <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline; padding: 0; font-size: inherit;">Supprimer ce projet</button>
            </form>
        </div>
    </div>
</main>
<?php renderFooter();

