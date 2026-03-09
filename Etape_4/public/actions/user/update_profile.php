<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('profile.php');
}

$sessionUser = currentUser();
$userId = isset($sessionUser['id']) ? (int) $sessionUser['id'] : 0;
if ($userId <= 0) {
    setFlash('error', 'Session utilisateur invalide.');
    redirect('index.php');
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));

if ($name === '' || $email === '') {
    setFlash('error', 'Nom et email sont obligatoires.');
    redirect('profile.php');
}

if (!preg_match('/^[a-zA-Z0-9._%+-]+@et\.esiea\.fr$/i', $email)) {
    setFlash('error', 'Email etudiant attendu (nom@et.esiea.fr).');
    redirect('profile.php');
}

if (userExistsByEmailExceptId($email, $userId)) {
    setFlash('error', 'Cet email est deja utilise.');
    redirect('profile.php');
}

userUpdateProfile($userId, $name, $email);

$_SESSION['user']['name'] = $name;
$_SESSION['user']['email'] = $email;

setFlash('success', 'Profil mis a jour.');
redirect('profile.php');
