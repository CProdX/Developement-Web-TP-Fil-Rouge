<?php

require_once __DIR__ . '/../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('project-create.php');
}

$nom = trim((string) ($_POST['nom'] ?? ''));
$client = trim((string) ($_POST['client'] ?? ''));
$description = trim((string) ($_POST['description'] ?? ''));

$_SESSION['old_project_form'] = [
    'nom' => $nom,
    'client' => $client,
    'description' => $description,
];

if ($nom === '' || $client === '' || $description === '') {
    setFlash('error', 'Tous les champs projet sont obligatoires.');
    redirect('project-create.php');
}

projectCreate($nom, $client, $description);

unset($_SESSION['old_project_form']);
setFlash('success', 'Projet cree en base de donnees.');
redirect('projects.php');
