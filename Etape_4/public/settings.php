<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

requireAuth();
$success = getFlash('success');
$error = getFlash('error');

$sessionUser = currentUser();
$userId = isset($sessionUser['id']) ? (int) $sessionUser['id'] : 0;
$user = $userId > 0 ? userFindById($userId) : null;

if ($user === null) {
    setFlash('error', 'Utilisateur introuvable.');
    redirect('index.php');
}

$settings = [
    'lang' => isset($user['lang']) ? (string) $user['lang'] : 'fr',
    'notif' => isset($user['notif']) ? (string) $user['notif'] : 'oui',
];

renderHeader('Parametres', 'PARAMETRES', 'settings');
?>
<main>
    <div class="cadre largeur-formulaire">
        <h2 class="titre-formulaire">Preferences</h2>

        <?php if ($success): ?>
            <div class="message-alerte message-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('update_settings_action.php')) ?>">
            <div class="champ-formulaire">
                <label for="lang">Langue</label>
                <select id="lang" name="lang">
                    <option value="fr" <?= $settings['lang'] === 'fr' ? 'selected' : '' ?>>Francais</option>
                    <option value="en" <?= $settings['lang'] === 'en' ? 'selected' : '' ?>>English</option>
                </select>
            </div>
            <div class="champ-formulaire">
                <label for="notif">Notifications</label>
                <select id="notif" name="notif">
                    <option value="oui" <?= $settings['notif'] === 'oui' ? 'selected' : '' ?>>Activees</option>
                    <option value="non" <?= $settings['notif'] === 'non' ? 'selected' : '' ?>>Desactivees</option>
                </select>
            </div>
            <button type="submit" class="bouton-large">Enregistrer</button>
        </form>
    </div>
</main>
<?php renderFooter();

