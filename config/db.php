<?php

declare(strict_types=1);

/**
 * Ajuste host, nome do banco, usuário e senha conforme seu ambiente (XAMPP, LAMP, Docker, etc.).
 */
const DB_HOST = 'localhost';
const DB_NAME = 'projeto_pets';
const DB_USER = 'vitor';
const DB_PASS = 'admin';

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_NAME
    );

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
