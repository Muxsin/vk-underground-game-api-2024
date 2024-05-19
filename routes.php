<?php

declare(strict_types=1);

use Muhsin\Vk\Controllers\GameController;
use Muhsin\Vk\Core\Request;
use Muhsin\Vk\Managers\JsonManager;
use Muhsin\Vk\Normalizers\MapNormalizer;
use Muhsin\Vk\Normalizers\MovementNormalizer;
use Muhsin\Vk\Routes\Route;
use Muhsin\Vk\Validators\MapValidator;
use Muhsin\Vk\Validators\MovementValidator;

$body = json_decode(file_get_contents('php://input'), true);

if($body === null) {
    http_response_code(400);

    echo json_encode(['error' => 'Bad request. Could not read the request body.']);

    exit;
}

$request = new Request($body);

$game_controller = new GameController(
    new MapValidator(),
    new MovementValidator(),
    new MapNormalizer(),
    new MovementNormalizer(),
    new JsonManager()
);

return [
    Route::post('/api/game/start', [$game_controller, 'initializeGame'], $request),
    Route::post('/api/game/move', [$game_controller, 'move'], $request),
    Route::get('/api/game/min-path', [$game_controller, 'minPath'], $request),
];
