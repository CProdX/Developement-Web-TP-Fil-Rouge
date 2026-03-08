<?php

require_once __DIR__ . '/../../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('register.php');
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$password = (string) ($_POST['password'] ?? '');

if ($name === '' || $email === '' || $password === '') {
    setFlash('error', 'Tous les champs sont obligatoires.');
    redirect('register.php');
}

if (!preg_match('/^[a-zA-Z0-9._%+-]+@et\.esiea\.fr$/i', $email)) {
    setFlash('error', 'Email etudiant attendu (nom@et.esiea.fr).');
    redirect('register.php');
}

if (strlen($password) < 8) {
    setFlash('error', 'Mot de passe trop court (8 caracteres min).');
    redirect('register.php');
}

$allUsers = getUsers();
if (isset($_SESSION['registered_users']) && is_array($_SESSION['registered_users'])) {
    $allUsers = array_merge($allUsers, $_SESSION['registered_users']);
}

foreach ($allUsers as $user) {
    if (strtolower((string) $user['email']) === $email) {
        setFlash('error', 'Cet email existe deja.');
        redirect('register.php');
    }
}

if (!isset($_SESSION['registered_users']) || !is_array($_SESSION['registered_users'])) {
    $_SESSION['registered_users'] = [];
}

$_SESSION['registered_users'][] = [
    'name' => $name,
    'email' => $email,
    'password' => $password,
    'role' => 'collaborateur',
];

setFlash('success', 'Inscription reussie.');
redirect('index.php');
