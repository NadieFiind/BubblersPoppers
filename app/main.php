<?php declare(strict_types=1);
require "vendor/autoload.php";
use app\controllers\Router;

Router::render($_SERVER["PATH_INFO"] ?? "/");
