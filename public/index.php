<?php
require_once __DIR__ . '/../app/routes/route.web.php';

session_start();

// Charger les données JSON
$dataFile = __DIR__ . '/../data/data.json';
$data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Gérer la route
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = str_replace('/ges-apprenant/public', '', $path);

handleRoute($route, $data);