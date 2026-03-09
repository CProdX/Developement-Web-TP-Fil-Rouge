<?php

session_start();

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/repositories/users_repository.php';
require_once __DIR__ . '/repositories/projects_repository.php';
require_once __DIR__ . '/repositories/tickets_repository.php';

initializeDatabase();
