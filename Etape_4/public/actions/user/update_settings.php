<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('settings.php');
}

$sessionUser = currentUser();
$userId = isset($sessionUser['id']) ? (int) $sessionUser['id'] : 0;
if ($userId <= 0) {
    setFlash('error', 'Session utilisateur invalide.');
    redirect('index.php');
}

$lang = (string) ($_POST['lang'] ?? '');
$notif = (string) ($_POST['notif'] ?? '');

if (!in_array($lang, ['fr', 'en'], true) || !in_array($notif, ['oui', 'non'], true)) {
    setFlash('error', 'Parametres invalides.');
    redirect('settings.php');
}

userUpdateSettings($userId, $lang, $notif);

setFlash('success', 'Parametres enregistres.');
redirect('settings.php');
