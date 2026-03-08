<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$ticketId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ticket = findTicketById($_SESSION['tickets'], $ticketId);

if ($ticket === null) {
    setFlash('error', 'Ticket introuvable.');
    redirect('tickets.php');
}

$project = findProjectById($_SESSION['projects'], $ticket['project_id']);
$projectText = $project ? projectLabel($project) : ticketProjectLabel($ticket, $_SESSION['projects']);
$success = getFlash('success');
$error = getFlash('error');

renderHeader('Detail ticket - Etape 3 PHP', 'DETAIL TICKET', 'tickets');
?>
<main>
    <div class="cadre">
        <?php if ($success): ?>
            <div class="message-alerte message-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <h2 class="titre-formulaire">Ticket #<?= e((string) $ticket['id']) ?> - <?= e((string) $ticket['sujet']) ?></h2>

        <div class="info-metier">
            <p><strong>Projet:</strong> <?= e($projectText) ?></p>
            <p><strong>Type:</strong> <?= e((string) $ticket['type']) ?></p>
            <p><strong>Priorite:</strong> <?= e((string) $ticket['priorite']) ?></p>
            <p><strong>Statut:</strong> <?= e((string) $ticket['statut']) ?></p>
            <p><strong>Temps:</strong> <?= e((string) $ticket['temps']) ?></p>
        </div>

        <h3>Description</h3>
        <p><?= nl2br(e((string) $ticket['description'])) ?></p>

        <div style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
            <a class="badge" href="<?= e(url('ticket-edit.php?id=' . (int) $ticket['id'])) ?>">Modifier</a>
            <a class="badge" href="<?= e(url('tickets.php')) ?>">Retour tickets</a>
            <?php if ($project): ?>
                <a class="badge" href="<?= e(url('project-detail.php?id=' . (int) $project['id'])) ?>">Voir projet</a>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php renderFooter();

