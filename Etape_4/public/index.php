<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

if (currentUser()) {
    redirect('dashboard.php');
}

$error = getFlash('error');
$success = getFlash('success');

renderHeader('Connexion - Etape 3 PHP', 'ESIEA TICKETING');
?>
<main class="centrer-flex">
    <div class="cadre largeur-login">
        <h2 class="titre-formulaire">IDENTIFICATION</h2>

        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message-alerte message-success message-bas-centre"><?= e($success) ?></div>
        <?php endif; ?>

        <form action="<?= e(url('actions/auth/login.php')) ?>" method="post">
            <div class="champ-formulaire">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nom@et.esiea.fr" required>
            </div>
            <div class="champ-formulaire">
                <label for="pass">Mot de passe</label>
                <input type="password" id="pass" name="password" required>
            </div>
            <button type="submit" class="bouton-large">Se connecter</button>
        </form>

        <p style="margin-top: 12px;">
            <a href="<?= e(url('forgot-password.php')) ?>">Mot de passe oublie?</a>
            |
            <a href="<?= e(url('register.php')) ?>">S'inscrire</a>
        </p>
    </div>
</main>
<?php renderFooter();
