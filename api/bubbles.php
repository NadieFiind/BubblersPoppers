<?php declare(strict_types=1);
require_once "../vendor/autoload.php";
Dotenv\Dotenv::createImmutable(__DIR__ . "/..")->safeLoad();

$db = new PDO(
    "mysql:dbname=bubblerspoppers;host={$_ENV['DB_HOST']}",
    $_ENV["DB_USER"] ?? null,
    $_ENV["DB_PASS"] ?? null
);
