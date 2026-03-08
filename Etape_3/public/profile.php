<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();
$user = currentUser();
$success = getFlash('success');
$error = getFlash('error');

renderHeader('Profil', 'MON PROFIL', 'profile');
?>
<main>
    <div class="cadre largeur-formulaire">
        <h2 class="titre-formulaire">Modifier mon profil</h2>

        <?php if ($success): ?>
            <div class="message-alerte message-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('actions/update_profile.php')) ?>">
            <div class="champ-formulaire">
                <label for="name">Nom complet</label>
                <input id="name" name="name" type="text" value="<?= e((string) $user['name']) ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?= e((string) $user['email']) ?>" required>
            </div>
            <div class="champ-formulaire">
                <label for="role">Role</label>
                <input id="role" type="text" value="<?= e((string) $user['role']) ?>" disabled>
            </div>
            <button type="submit" class="bouton-large">Enregistrer</button>
        </form>
    </div>
</main>
<?php renderFooter();

