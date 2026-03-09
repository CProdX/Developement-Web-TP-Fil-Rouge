<?php

require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('forgot-password.php');
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));

if ($email === '') {
    setFlash('error', "L'email est obligatoire.");
    redirect('forgot-password.php');
}

if (!preg_match('/^[a-zA-Z0-9._%+-]+@et\.esiea\.fr$/i', $email)) {
    setFlash('error', 'Email etudiant attendu (nom@et.esiea.fr).');
    redirect('forgot-password.php');
}

setFlash('success', 'Demande envoyee.');
redirect('index.php');
