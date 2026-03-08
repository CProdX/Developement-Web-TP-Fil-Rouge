<?php

require_once __DIR__ . '/../../includes/bootstrap.php';

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

$projects = $_SESSION['projects'];
$newId = nextProjectId($projects);

$_SESSION['projects'][] = [
    'id' => $newId,
    'nom' => $nom,
    'client' => $client,
    'heures_contrat' => 0,
    'heures_consommees' => 0,
    'description' => $description,
    'statut' => 'Actif',
];

unset($_SESSION['old_project_form']);
setFlash('success', 'Projet cree.');
redirect('projects.php');
