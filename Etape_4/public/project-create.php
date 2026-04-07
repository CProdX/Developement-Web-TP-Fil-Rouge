<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();

$error = getFlash('error');
$formData = isset($_SESSION['old_project_form']) ? $_SESSION['old_project_form'] : [];
unset($_SESSION['old_project_form']);

renderHeader('Nouveau projet', 'CREER UN PROJET', 'projects');
?>
<main>
    <div class="cadre largeur-formulaire">
        <h2 class="titre-formulaire">Nouveau projet</h2>

        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('actions/projects/create_project.php')) ?>">
            <div class="champ-formulaire">
                <label for="nom">Nom du projet</label>
                <input id="nom" name="nom" type="text" value="<?= e(isset($formData['nom']) ? $formData['nom'] : '') ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="client">Client</label>
                <input id="client" name="client" type="text" value="<?= e(isset($formData['client']) ? $formData['client'] : '') ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required><?= e(isset($formData['description']) ? $formData['description'] : '') ?></textarea>
            </div>
            <button type="submit" class="bouton-large">Creer le projet</button>
        </form>
    </div>
</main>
<?php renderFooter();

