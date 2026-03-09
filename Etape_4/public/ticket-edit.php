<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$ticketId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ticket = ticketFindById($ticketId);

if ($ticket === null) {
    setFlash('error', 'Ticket introuvable.');
    redirect('tickets.php');
}

$error = getFlash('error');

renderHeader('Modifier ticket - Etape 4 PHP', 'MODIFIER UN TICKET', 'tickets');
?>
<main>
    <div class="cadre largeur-formulaire">
        <h2 class="titre-formulaire">Edition ticket #<?= e((string) $ticket['id']) ?></h2>

        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="<?= e(url('actions/tickets/update_ticket.php')) ?>" method="post">
            <input type="hidden" name="id" value="<?= e((string) $ticket['id']) ?>">

            <div class="champ-formulaire">
                <label for="sujet_readonly">Sujet</label>
                <input id="sujet_readonly" type="text" value="<?= e((string) $ticket['sujet']) ?>" disabled>
            </div>

            <div class="champ-formulaire">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" required>
                    <?php foreach (['Nouveau', 'En cours', 'En attente client', 'Termine', 'A valider', 'Valide', 'Refuse'] as $value): ?>
                        <option value="<?= e($value) ?>" <?= $ticket['statut'] === $value ? 'selected' : '' ?>><?= e($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="priorite">Priorite</label>
                <select id="priorite" name="priorite" required>
                    <?php foreach (['Basse', 'Moyenne', 'Critique'] as $value): ?>
                        <option value="<?= e($value) ?>" <?= $ticket['priorite'] === $value ? 'selected' : '' ?>><?= e($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="temps">Temps passe (HH:MM)</label>
                <input type="text" id="temps" name="temps" value="<?= e((string) $ticket['temps']) ?>" placeholder="02:30" required>
            </div>

            <div class="champ-formulaire">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="6" required><?= e((string) $ticket['description']) ?></textarea>
            </div>

            <button type="submit" class="bouton-large">Enregistrer</button>
        </form>

        <p style="margin-top: 16px;"><a href="<?= e(url('ticket-detail.php?id=' . (int) $ticket['id'])) ?>">Annuler et retour au detail</a></p>
    </div>
</main>
<?php renderFooter();

