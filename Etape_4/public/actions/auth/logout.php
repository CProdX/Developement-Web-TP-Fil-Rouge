<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

unset($_SESSION['user']);
setFlash('success', 'Deconnexion.');
redirect('index.php');
