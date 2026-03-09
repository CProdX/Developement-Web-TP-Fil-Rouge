<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('ticket-create.php');
}

$titre = trim((string) ($_POST['titre'] ?? ''));
$type = trim((string) ($_POST['type'] ?? ''));
$projectId = (int) ($_POST['project_id'] ?? 0);
$priorite = trim((string) ($_POST['priorite'] ?? ''));
$description = trim((string) ($_POST['description'] ?? ''));

$_SESSION['old_ticket_form'] = [
    'titre' => $titre,
    'type' => $type,
    'project_id' => $projectId,
    'priorite' => $priorite,
    'description' => $description,
];

if ($titre === '' || $type === '' || $projectId <= 0 || $priorite === '' || $description === '') {
    setFlash('error', 'Tous les champs ticket sont obligatoires.');
    redirect('ticket-create.php');
}

if (!in_array($type, ['Inclus', 'Facturable'], true)) {
    setFlash('error', 'Type de ticket invalide.');
    redirect('ticket-create.php');
}

if (!in_array($priorite, ['Basse', 'Moyenne', 'Critique'], true)) {
    setFlash('error', 'Priorite invalide.');
    redirect('ticket-create.php');
}

$project = projectFindById($projectId);
if ($project === null) {
    setFlash('error', 'Projet introuvable pour ce ticket.');
    redirect('ticket-create.php');
}

ticketCreate($titre, $type, $projectId, $priorite, $description);

unset($_SESSION['old_ticket_form']);
setFlash('success', 'Ticket cree en base de donnees.');
redirect('tickets.php');
