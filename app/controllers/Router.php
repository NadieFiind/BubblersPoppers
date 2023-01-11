<?php declare(strict_types=1);
namespace app\controllers;

const ROUTES_MAP = [
    "/" => "app/views/pages/Home.php",
    "/home" => "app/views/pages/Home.php",
    "/stats" => "app/views/pages/Stats.php",
    "/404" => "app/views/pages/404.php"
];

class Router
{
    static function render(string $path): void
    {
        if (array_key_exists($path, ROUTES_MAP)) {
            include ROUTES_MAP[$path];
        } else {
            http_response_code(404);
            include ROUTES_MAP["/404"];
        }
    }
}
