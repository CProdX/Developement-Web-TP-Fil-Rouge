<?php

function minutesToHHMM($minutes)
{
    $totalMinutes = max(0, (int) $minutes);
    $hours = (int) floor($totalMinutes / 60);
    $remainingMinutes = $totalMinutes % 60;

    return sprintf('%02d:%02d', $hours, $remainingMinutes);
}

function hhmmToMinutes($time)
{
    if (!preg_match('/^\d{1,3}:\d{2}$/', (string) $time)) {
        return null;
    }

    [$hours, $minutes] = array_map('intval', explode(':', (string) $time));
    if ($minutes < 0 || $minutes > 59) {
        return null;
    }

    return ($hours * 60) + $minutes;
}

function ticketHydrateForView(array $ticket)
{
    $ticket['temps'] = minutesToHHMM((int) ($ticket['temps_minutes'] ?? 0));
    unset($ticket['temps_minutes']);

    return $ticket;
}

function ticketFindAll()
{
    $sql = '
        SELECT
            t.id,
            t.sujet,
            t.type,
            t.priorite,
            t.statut,
            t.project_id,
            t.description,
            t.temps_minutes
        FROM tickets t
        ORDER BY t.id ASC
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    return array_map('ticketHydrateForView', $rows);
}

function ticketFindById($ticketId)
{
    $sql = '
        SELECT
            t.id,
            t.sujet,
            t.type,
            t.priorite,
            t.statut,
            t.project_id,
            t.description,
            t.temps_minutes
        FROM tickets t
        WHERE t.id = :ticket_id
        LIMIT 1
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':ticket_id', (int) $ticketId, PDO::PARAM_INT);
    $stmt->execute();
    $ticket = $stmt->fetch();

    if (!$ticket) {
        return null;
    }

    return ticketHydrateForView($ticket);
}

function ticketCreate($sujet, $type, $projectId, $priorite, $description)
{
    $sql = '
        INSERT INTO tickets (project_id, sujet, type, priorite, statut, description, temps_minutes)
        VALUES (:project_id, :sujet, :type, :priorite, :statut, :description, :temps_minutes)
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':project_id', (int) $projectId, PDO::PARAM_INT);
    $stmt->bindValue(':sujet', (string) $sujet, PDO::PARAM_STR);
    $stmt->bindValue(':type', (string) $type, PDO::PARAM_STR);
    $stmt->bindValue(':priorite', (string) $priorite, PDO::PARAM_STR);
    $stmt->bindValue(':statut', 'Nouveau', PDO::PARAM_STR);
    $stmt->bindValue(':description', (string) $description, PDO::PARAM_STR);
    $stmt->bindValue(':temps_minutes', 0, PDO::PARAM_INT);
    $stmt->execute();

    return (int) getPDO()->lastInsertId();
}

function ticketUpdate($ticketId, $statut, $priorite, $tempsHHMM, $description)
{
    $minutes = hhmmToMinutes($tempsHHMM);
    if ($minutes === null) {
        return false;
    }

    $sql = '
        UPDATE tickets
        SET statut = :statut,
            priorite = :priorite,
            temps_minutes = :temps_minutes,
            description = :description,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :ticket_id
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':statut', (string) $statut, PDO::PARAM_STR);
    $stmt->bindValue(':priorite', (string) $priorite, PDO::PARAM_STR);
    $stmt->bindValue(':temps_minutes', $minutes, PDO::PARAM_INT);
    $stmt->bindValue(':description', (string) $description, PDO::PARAM_STR);
    $stmt->bindValue(':ticket_id', (int) $ticketId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

function ticketDelete($ticketId)
{
    $sql = 'DELETE FROM tickets WHERE id = :ticket_id';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':ticket_id', (int) $ticketId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

