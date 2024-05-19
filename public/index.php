<?php

use Muhsin\Vk\Routes\Router;

require __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];

$router = new Router(require __DIR__ . '/../routes.php');
$router->dispatch($uri, $method);
