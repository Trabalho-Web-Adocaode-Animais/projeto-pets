<?php

declare(strict_types=1);

/**
 * Router para o servidor embutido do PHP: encaminha rotas inexistentes para index.php.
 * Uso na raiz do projeto: php -S localhost:8000 -t public public/router.php
 */
$uri = urldecode((string) (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'));
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

require __DIR__ . '/index.php';
