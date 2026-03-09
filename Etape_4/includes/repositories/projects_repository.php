<?php

function projectFindAll()
{
    $sql = '
        SELECT
            p.id,
            p.nom,
            c.name AS client,
            p.description,
            p.statut,
            COALESCE(ct.heures_incluses, 0) AS heures_contrat,
            COALESCE(ROUND(SUM(t.temps_minutes) / 60.0, 2), 0) AS heures_consommees
        FROM projects p
        INNER JOIN clients c ON c.id = p.client_id
        LEFT JOIN contrats ct ON ct.id = p.contrat_id
        LEFT JOIN tickets t ON t.project_id = p.id
        GROUP BY p.id, p.nom, c.name, p.description, p.statut, ct.heures_incluses
        ORDER BY p.id ASC
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

function projectFindById($projectId)
{
    $sql = '
        SELECT
            p.id,
            p.nom,
            c.name AS client,
            p.description,
            p.statut,
            COALESCE(ct.heures_incluses, 0) AS heures_contrat,
            COALESCE(ROUND(SUM(t.temps_minutes) / 60.0, 2), 0) AS heures_consommees
        FROM projects p
        INNER JOIN clients c ON c.id = p.client_id
        LEFT JOIN contrats ct ON ct.id = p.contrat_id
        LEFT JOIN tickets t ON t.project_id = p.id
        WHERE p.id = :project_id
        GROUP BY p.id, p.nom, c.name, p.description, p.statut, ct.heures_incluses
        LIMIT 1
    ';

    $stmt = getPDO()->prepare($sql);
    $stmt->bindValue(':project_id', (int) $projectId, PDO::PARAM_INT);
    $stmt->execute();
    $project = $stmt->fetch();

    return $project ?: null;
}

function projectCreate($nom, $clientName, $description)
{
    $pdo = getPDO();
    $pdo->beginTransaction();

    try {
        $clientStmt = $pdo->prepare('SELECT id FROM clients WHERE name = :name LIMIT 1');
        $clientStmt->bindValue(':name', (string) $clientName, PDO::PARAM_STR);
        $clientStmt->execute();
        $client = $clientStmt->fetch();

        if (!$client) {
            $insertClientStmt = $pdo->prepare('INSERT INTO clients (name) VALUES (:name)');
            $insertClientStmt->bindValue(':name', (string) $clientName, PDO::PARAM_STR);
            $insertClientStmt->execute();
            $clientId = (int) $pdo->lastInsertId();

            $contractStmt = $pdo->prepare('
                INSERT INTO contrats (client_id, label, heures_incluses)
                VALUES (:client_id, :label, 0)
            ');
            $contractStmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $contractStmt->bindValue(':label', 'Contrat ' . (string) $clientName, PDO::PARAM_STR);
            $contractStmt->execute();
            $contractId = (int) $pdo->lastInsertId();
        } else {
            $clientId = (int) $client['id'];
            $contractStmt = $pdo->prepare('SELECT id FROM contrats WHERE client_id = :client_id ORDER BY id ASC LIMIT 1');
            $contractStmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
            $contractStmt->execute();
            $contract = $contractStmt->fetch();

            if ($contract) {
                $contractId = (int) $contract['id'];
            } else {
                $insertContractStmt = $pdo->prepare('
                    INSERT INTO contrats (client_id, label, heures_incluses)
                    VALUES (:client_id, :label, 0)
                ');
                $insertContractStmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
                $insertContractStmt->bindValue(':label', 'Contrat ' . (string) $clientName, PDO::PARAM_STR);
                $insertContractStmt->execute();
                $contractId = (int) $pdo->lastInsertId();
            }
        }

        $projectStmt = $pdo->prepare('
            INSERT INTO projects (client_id, contrat_id, nom, description, statut)
            VALUES (:client_id, :contrat_id, :nom, :description, :statut)
        ');
        $projectStmt->bindValue(':client_id', $clientId, PDO::PARAM_INT);
        $projectStmt->bindValue(':contrat_id', $contractId, PDO::PARAM_INT);
        $projectStmt->bindValue(':nom', (string) $nom, PDO::PARAM_STR);
        $projectStmt->bindValue(':description', (string) $description, PDO::PARAM_STR);
        $projectStmt->bindValue(':statut', 'Actif', PDO::PARAM_STR);
        $projectStmt->execute();

        $projectId = (int) $pdo->lastInsertId();
        $pdo->commit();

        return $projectId;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

function projectDelete($projectId)
{
    $pdo = getPDO();
    $pdo->beginTransaction();

    try {
        // Supprimer d'abord tous les tickets du projet
        $deleteTicketsStmt = $pdo->prepare('DELETE FROM tickets WHERE project_id = :project_id');
        $deleteTicketsStmt->bindValue(':project_id', (int) $projectId, PDO::PARAM_INT);
        $deleteTicketsStmt->execute();

        // Ensuite supprimer le projet
        $deleteProjectStmt = $pdo->prepare('DELETE FROM projects WHERE id = :project_id');
        $deleteProjectStmt->bindValue(':project_id', (int) $projectId, PDO::PARAM_INT);
        $deleteProjectStmt->execute();

        $pdo->commit();

        return $deleteProjectStmt->rowCount() > 0;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }
}

