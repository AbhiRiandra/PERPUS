<?php
// Set working directory ke root project
chdir(__DIR__ . '/..');

// Pastikan variabel server mengarah ke root index.php untuk routing CI3 yang benar
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF']    = '/index.php';

// Panggil file utama CodeIgniter 3
require __DIR__ . '/../index.php';

