<?php

session_start();

require_once __DIR__ . '/../data/users.php';
require_once __DIR__ . '/../data/projects.php';
require_once __DIR__ . '/../data/tickets.php';
require_once __DIR__ . '/helpers.php';

if (!isset($_SESSION['projects']) || !is_array($_SESSION['projects'])) {
    $_SESSION['projects'] = getInitialProjects();
}

if (!isset($_SESSION['tickets']) || !is_array($_SESSION['tickets'])) {
    $_SESSION['tickets'] = getInitialTickets();
}

// Migration douce: rattache les anciens tickets a un projet par nom si besoin.
$projectNamesToIds = [];
foreach ($_SESSION['projects'] as $project) {
    $label = trim((string) ($project['client'] . ' - ' . $project['nom']));
    $projectNamesToIds[strtolower($label)] = (int) $project['id'];
}

foreach ($_SESSION['tickets'] as $index => $ticket) {
    if (isset($ticket['project_id']) && $ticket['project_id'] !== '') {
        continue;
    }

    $legacyName = isset($ticket['projet']) ? strtolower(trim((string) $ticket['projet'])) : '';
    $_SESSION['tickets'][$index]['project_id'] = isset($projectNamesToIds[$legacyName])
        ? $projectNamesToIds[$legacyName]
        : (int) $_SESSION['projects'][0]['id'];
}
