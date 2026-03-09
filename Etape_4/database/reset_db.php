<?php

require_once __DIR__ . '/../includes/db.php';

$pdo = getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('DROP TABLE IF EXISTS temps_passes');
$pdo->exec('DROP TABLE IF EXISTS tickets');
$pdo->exec('DROP TABLE IF EXISTS projects');
$pdo->exec('DROP TABLE IF EXISTS contrats');
$pdo->exec('DROP TABLE IF EXISTS clients');
$pdo->exec('DROP TABLE IF EXISTS users');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$schemaPath = __DIR__ . '/001_schema.sql';
$seedPath = __DIR__ . '/002_seed.sql';

if (!file_exists($schemaPath) || !file_exists($seedPath)) {
    fwrite(STDERR, "Fichiers SQL introuvables.\n");
    exit(1);
}

$pdo->exec((string) file_get_contents($schemaPath));
$pdo->exec((string) file_get_contents($seedPath));

echo "Base MySQL reinitialisee avec succes.\n";
