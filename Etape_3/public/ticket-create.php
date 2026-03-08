<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$error = getFlash('error');
$formData = isset($_SESSION['old_ticket_form']) ? $_SESSION['old_ticket_form'] : [];
unset($_SESSION['old_ticket_form']);
$projects = $_SESSION['projects'];

renderHeader('Creer un ticket - Etape 3 PHP', 'OUVRIR UN TICKET', 'tickets');
?>
<main>
    <div class="cadre largeur-formulaire">
        <h2 class="titre-formulaire">Nouvelle Demande</h2>

        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="<?= e(url('actions/create_ticket.php')) ?>" method="post">
            <div class="champ-formulaire">
                <label for="titre">Sujet du ticket</label>
                <input type="text" id="titre" name="titre" value="<?= e(isset($formData['titre']) ? $formData['titre'] : '') ?>" required>
            </div>

            <div class="champ-formulaire">
                <label for="type">Type de prestation</label>
                <select id="type" name="type" required>
                    <option value="Inclus" <?= ((isset($formData['type']) ? $formData['type'] : '') === 'Inclus') ? 'selected' : '' ?>>Inclus dans le contrat</option>
                    <option value="Facturable" <?= ((isset($formData['type']) ? $formData['type'] : '') === 'Facturable') ? 'selected' : '' ?>>Hors contrat (Facturable)</option>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="project_id">Projet associe</label>
                <select id="project_id" name="project_id" required>
                    <option value="">Selectionner un projet</option>
                    <?php foreach ($projects as $project): ?>
                        <?php $selectedProjectId = isset($formData['project_id']) ? (int) $formData['project_id'] : 0; ?>
                        <option value="<?= e((string) $project['id']) ?>" <?= $selectedProjectId === (int) $project['id'] ? 'selected' : '' ?>>
                            <?= e(projectLabel($project)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="priorite">Priorite</label>
                <select id="priorite" name="priorite" required>
                    <option value="Basse" <?= ((isset($formData['priorite']) ? $formData['priorite'] : '') === 'Basse') ? 'selected' : '' ?>>Basse</option>
                    <option value="Moyenne" <?= ((isset($formData['priorite']) ? $formData['priorite'] : '') === 'Moyenne') ? 'selected' : '' ?>>Moyenne</option>
                    <option value="Critique" <?= ((isset($formData['priorite']) ? $formData['priorite'] : '') === 'Critique') ? 'selected' : '' ?>>Critique</option>
                </select>
            </div>

            <div class="champ-formulaire">
                <label for="desc">Description detaillee</label>
                <textarea id="desc" name="description" rows="5" required><?= e(isset($formData['description']) ? $formData['description'] : '') ?></textarea>
            </div>

            <button type="submit" class="bouton-large">Soumettre</button>
        </form>
    </div>
</main>
<?php renderFooter();

