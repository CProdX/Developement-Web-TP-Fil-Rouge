<?php

require_once __DIR__ . '/../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('projects.php');
}

$projectId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($projectId === 0) {
    setFlash('error', 'ID de projet invalide.');
    redirect('projects.php');
}

$project = projectFindById($projectId);

if ($project === null) {
    setFlash('error', 'Projet introuvable.');
    redirect('projects.php');
}

try {
    $deleted = projectDelete($projectId);

    if ($deleted) {
        setFlash('success', 'Projet supprime avec succes.');
    } else {
        setFlash('error', 'Erreur lors de la suppression du projet.');
    }
} catch (Throwable $e) {
    setFlash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
}

redirect('projects.php');

