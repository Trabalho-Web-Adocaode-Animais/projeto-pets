<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';

$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($rawPath) ? $rawPath : '/';
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

$userModel = new User(getPdo());
$auth = new AuthController($userModel);

match ($path) {
    '/cadastro' => $auth->register(),
    '/login' => $auth->login(),
    '/logout' => $auth->logout(),
    default => require __DIR__ . '/../src/Views/home.php',
};

