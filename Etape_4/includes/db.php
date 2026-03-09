<?php

function getDatabaseConfig()
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $configPath = __DIR__ . '/../config/database.php';
    if (!file_exists($configPath)) {
        throw new RuntimeException('Configuration BDD introuvable.');
    }

    $config = require $configPath;

    return $config;
}

function getPDO()
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $config = getDatabaseConfig();
    if (($config['driver'] ?? '') !== 'mysql') {
        throw new RuntimeException('Driver BDD non supporte (mysql attendu).');
    }

    $host = (string) ($config['host'] ?? '127.0.0.1');
    $port = (string) ($config['port'] ?? '3306');
    $database = (string) ($config['database'] ?? '');
    $username = (string) ($config['username'] ?? '');
    $password = (string) ($config['password'] ?? '');
    $charset = (string) ($config['charset'] ?? 'utf8mb4');

    if ($database === '' || $username === '') {
        throw new RuntimeException('Configuration MySQL incomplete.');
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $database, $charset);

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function ensureUsersPreferencesColumns(PDO $pdo)
{
    $columnsStmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'lang'");
    $hasLang = $columnsStmt->fetchColumn() !== false;

    if (!$hasLang) {
        $pdo->exec("ALTER TABLE users ADD COLUMN lang VARCHAR(5) NOT NULL DEFAULT 'fr' AFTER role");
    }

    $columnsStmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'notif'");
    $hasNotif = $columnsStmt->fetchColumn() !== false;

    if (!$hasNotif) {
        $pdo->exec("ALTER TABLE users ADD COLUMN notif VARCHAR(3) NOT NULL DEFAULT 'oui' AFTER lang");
    }
}

function initializeDatabase()
{
    $pdo = getPDO();
    $stmt = $pdo->query("SHOW TABLES LIKE 'tickets'");
    $exists = $stmt->fetchColumn();

    if ($exists === false) {
        $schemaPath = __DIR__ . '/../database/001_schema.sql';
        $seedPath = __DIR__ . '/../database/002_seed.sql';

        if (!file_exists($schemaPath) || !file_exists($seedPath)) {
            throw new RuntimeException('Fichiers SQL manquants pour initialiser la base.');
        }

        $pdo->exec((string) file_get_contents($schemaPath));
        $pdo->exec((string) file_get_contents($seedPath));
    }

    ensureUsersPreferencesColumns($pdo);
}
