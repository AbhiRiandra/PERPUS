<?php
// Set working directory ke root project
chdir(__DIR__ . '/..');

// Normalisasi variabel server agar CodeIgniter 3 mengenali root index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF']    = '/index.php';

if (!isset($_SERVER['REQUEST_URI']) || empty($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = '/';
}

// Panggil file utama CodeIgniter 3
require __DIR__ . '/../index.php';
