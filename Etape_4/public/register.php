<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/layout.php';

$success = getFlash('success');
$error = getFlash('error');

renderHeader('Inscription', 'INSCRIPTION');
?>
<main class="centrer-flex">
    <div class="cadre largeur-login">
        <h2 class="titre-formulaire">Creer un compte</h2>

        <?php if ($success): ?>
            <div class="message-alerte message-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message-alerte message-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('actions/auth/register.php')) ?>">
            <div class="champ-formulaire">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="champ-formulaire">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="nom@et.esiea.fr" required>
            </div>
            <div class="champ-formulaire">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="bouton-large">S'inscrire</button>
        </form>

        <p style="margin-top: 16px;"><a href="<?= e(url('index.php')) ?>">Retour connexion</a></p>
    </div>
</main>
<?php renderFooter();

