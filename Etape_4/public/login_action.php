<?php

require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$email = isset($_POST['email']) ? strtolower(trim((string) $_POST['email'])) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';

if ($email === '') {
    setFlash('error', "L'email est obligatoire.");
    redirect('index.php');
}

if (!preg_match('/^[a-zA-Z0-9._%+-]+@et\.esiea\.fr$/i', $email)) {
    setFlash('error', 'Email etudiant attendu (nom@et.esiea.fr).');
    redirect('index.php');
}

if ($password === '') {
    setFlash('error', 'Le mot de passe est obligatoire.');
    redirect('index.php');
}

$authenticatedUser = userFindByCredentials($email, $password);

if ($authenticatedUser === null) {
    setFlash('error', 'Identifiants invalides.');
    redirect('index.php');
}

$_SESSION['user'] = [
    'id' => (int) $authenticatedUser['id'],
    'email' => (string) $authenticatedUser['email'],
    'name' => (string) $authenticatedUser['name'],
    'role' => (string) $authenticatedUser['role'],
];

setFlash('success', 'Connexion reussie.');
redirect('dashboard.php');
