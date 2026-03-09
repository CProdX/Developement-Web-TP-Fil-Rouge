<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tickets.php');
}

$ticketId = (int) ($_POST['id'] ?? 0);
$statut = trim((string) ($_POST['statut'] ?? ''));
$priorite = trim((string) ($_POST['priorite'] ?? ''));
$temps = trim((string) ($_POST['temps'] ?? ''));
$description = trim((string) ($_POST['description'] ?? ''));

if ($ticketId <= 0 || $statut === '' || $priorite === '' || $temps === '' || $description === '') {
    setFlash('error', 'Tous les champs sont obligatoires pour la mise a jour.');
    redirect('ticket-edit.php?id=' . $ticketId);
}

if (!in_array($priorite, ['Basse', 'Moyenne', 'Critique'], true)) {
    setFlash('error', 'Priorite invalide.');
    redirect('ticket-edit.php?id=' . $ticketId);
}

if (!in_array($statut, ['Nouveau', 'En cours', 'En attente client', 'Termine', 'A valider', 'Valide', 'Refuse'], true)) {
    setFlash('error', 'Statut invalide.');
    redirect('ticket-edit.php?id=' . $ticketId);
}

$ticket = ticketFindById($ticketId);
if ($ticket === null) {
    setFlash('error', 'Ticket introuvable.');
    redirect('tickets.php');
}

if (!ticketUpdate($ticketId, $statut, $priorite, $temps, $description)) {
    setFlash('error', 'Format du temps invalide. Utilisez HH:MM.');
    redirect('ticket-edit.php?id=' . $ticketId);
}

setFlash('success', 'Ticket mis a jour en base de donnees.');
redirect('ticket-detail.php?id=' . $ticketId);
