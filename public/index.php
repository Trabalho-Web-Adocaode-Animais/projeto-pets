<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($rawPath) ? $rawPath : '/';
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

$pdo = getPdo();

$userModel = new User($pdo);
$auth = new AuthController($userModel);

$petModel = new Pet($pdo);
$petController = new PetController($petModel);

$vaccineModel = new Vaccine($pdo);
$vaccineController = new VaccineController($vaccineModel, $petModel);

match ($path) {
    '/' => $petController->home(),
    '/cadastro' => $auth->register(),
    '/login' => $auth->login(),
    '/logout' => $auth->logout(),
    '/pets/novo' => $petController->create(),
    '/pets/meus' => $petController->meus(),
    '/pets/editar' => $petController->edit(),
    '/pets/status' => $petController->toggleStatus(),
    '/vacinas' => $vaccineController->index(),
    '/vacinas/nova' => $vaccineController->create(),
    
    default => $petController->home(),
};