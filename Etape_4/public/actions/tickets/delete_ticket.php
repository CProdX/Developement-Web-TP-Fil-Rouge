<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tickets.php');
}

$ticketId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($ticketId === 0) {
    setFlash('error', 'ID de ticket invalide.');
    redirect('tickets.php');
}

$ticket = ticketFindById($ticketId);

if ($ticket === null) {
    setFlash('error', 'Ticket introuvable.');
    redirect('tickets.php');
}

try {
    $deleted = ticketDelete($ticketId);

    if ($deleted) {
        setFlash('success', 'Ticket supprime avec succes.');
    } else {
        setFlash('error', 'Erreur lors de la suppression du ticket.');
    }
} catch (Throwable $e) {
    setFlash('error', 'Erreur lors de la suppression: ' . $e->getMessage());
}

redirect('tickets.php');

