<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url($path = '')
{
    // Retourner un chemin relatif simple pour fonctionner avec le serveur PHP intégré
    $path = ltrim((string) $path, '/');
    return $path ?: 'index.php';
}

function redirect($path)
{
    // Générer un chemin absolu depuis la racine web
    if (preg_match('/^https?:\/\//i', (string) $path)) {
        // URL absolue
        $target = $path;
    } else {
        // Chemin relatif - le convertir en URL absolue relative à la racine
        $path = ltrim((string) $path, '/');
        $target = '/' . $path;
    }
    header('Location: ' . $target);
    exit;
}

function setFlash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

function getFlash($key)
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $message;
}

function currentUser()
{
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function requireAuth()
{
    if (!currentUser()) {
        setFlash('error', 'Connexion requise.');
        redirect('index.php');
    }
}

function findUserByCredentials($email, $password)
{
    foreach (getUsers() as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return $user;
        }
    }

    return null;
}

function nextTicketId($tickets)
{
    $ids = array_column($tickets, 'id');
    if ($ids === []) {
        return 8800;
    }

    return max($ids) + 1;
}

function nextProjectId($projects)
{
    $ids = array_column($projects, 'id');
    if ($ids === []) {
        return 100;
    }

    return max($ids) + 1;
}

function findProjectById($projects, $id)
{
    foreach ($projects as $project) {
        if ((int) $project['id'] === (int) $id) {
            return $project;
        }
    }

    return null;
}

function findTicketById($tickets, $id)
{
    foreach ($tickets as $ticket) {
        if ((int) $ticket['id'] === (int) $id) {
            return $ticket;
        }
    }

    return null;
}

function findTicketIndexById($tickets, $id)
{
    foreach ($tickets as $index => $ticket) {
        if ((int) $ticket['id'] === (int) $id) {
            return $index;
        }
    }

    return null;
}

function projectLabel($project)
{
    return trim((string) ($project['client'] . ' - ' . $project['nom']));
}

function ticketProjectLabel($ticket, $projects)
{
    $project = findProjectById($projects, isset($ticket['project_id']) ? $ticket['project_id'] : null);
    if ($project) {
        return projectLabel($project);
    }

    return isset($ticket['projet']) ? (string) $ticket['projet'] : 'Projet inconnu';
}
