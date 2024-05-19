<?php

declare(strict_types=1);

namespace Muhsin\Vk\Controllers;

use Muhsin\Vk\Core\Request;
use Muhsin\Vk\Managers\FileManager;
use Muhsin\Vk\Models\Map;
use Muhsin\Vk\Models\Player;
use Muhsin\Vk\Normalizers\NormalizerInterface;
use Muhsin\Vk\Validators\ValidatorInterface;

class GameController
{
    private ValidatorInterface $map_validator;
    private NormalizerInterface $map_normalizer;
    private NormalizerInterface $movement_normalizer;
    private FileManager $json_manager;
    private ValidatorInterface $movement_validator;

    public function __construct(
        ValidatorInterface $map_validator,
        ValidatorInterface $movement_validator,
        NormalizerInterface $map_normalizer,
        NormalizerInterface $movement_normalizer,
        FileManager $json_manager
    ) {
        $this->map_validator = $map_validator;
        $this->map_normalizer = $map_normalizer;
        $this->movement_normalizer = $movement_normalizer;
        $this->json_manager = $json_manager;
        $this->movement_validator = $movement_validator;
    }

    public function initializeGame(Request $request): void
    {
        if(!$this->map_validator->validate($request->all())) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request. Could not read the request body.']);
            exit;
        }

        $map = $this->map_normalizer->normalize($request->all());
        $player = new Player(0, $map->getStartingRoom());

        $produced_player = $this->json_manager->write(
            __DIR__ . '\..\Queues\PlayerDB.json',
            ['points' => $player->getPoints(), 'position' => $player->getPosition()]
        );
        $produced_map = $this->json_manager->write(__DIR__ . '\..\Queues\MapDB.json', $map->getMap());

        if ($produced_map === false || $produced_player === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error. Something went wrong.']);
            exit;
        }

        http_response_code(204);
    }

    public function move(Request $request): void
    {
        if(!$this->movement_validator->validate($request->all())) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request. Could not read the request body.']);
            exit;
        }

        $room_number = $this->movement_normalizer->normalize($request->all());

        $consumed_player = $this->json_manager->read(__DIR__ . '\..\..\db\PlayerDB.json');
        $consumed_map = $this->json_manager->read(__DIR__ . '\..\..\db\MapDB.json');

        if ($consumed_map === false || $consumed_player === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal server error. Something went wrong.']);
            exit;
        }

        $map = new Map($consumed_map);
        $player = new Player($consumed_player['points'], $consumed_player['position']);

        if($map->canMoveToRoom($player->getPosition(), $room_number)) {
            if($room_number === $map->getExitRoom()) {
                $min_path = $this->minPath();

                echo json_encode([
                    'message' => "You won! Score: {$player->getPoints()}",
                    'min_path' => $min_path
                ]);

                exit;
            }

            $player->setPosition($room_number);

            $next_room = $map->getRoomByNumber($room_number);

            if(!$next_room['visited'] && $next_room['type'] === 'chest') {
                $map->makeVisited($next_room['room_number']);

                $rand_number = rand(0, 100);

                if($rand_number <= 50) {
                    $player->increasePoint(rand(100, 150));
                } else if ($rand_number < 90) {
                    $player->increasePoint(rand(200, 250));
                } else {
                    $player->increasePoint(rand(350, 400));
                }
            } else if (!$next_room['visited'] && $next_room['type'] === 'monster') {
                $map->makeVisited($next_room['room_number']);

                $monster_power = rand(500, 1000);
                $player_damage = rand(500, 1500);

                if ($monster_power < $player_damage) {
                    $player->increasePoint($player_damage);
                } else {
                    $round = 1;

                    // Я использую цикл для повторения раунда, поскольку в условии задачи указано, что раунд должен продолжаться до победы над монстром
                    while ($monster_power > $player_damage) {
                        $monster_power -= $player_damage;
                        $round++;
                    }

                    $player->increasePoint($monster_power);
                }
            }

            $produced_player = $this->json_manager->write(
                __DIR__ . '\..\..\db\PlayerDB.json',
                ['points' => $player->getPoints(), 'position' => $player->getPosition()]
            );
            $produced_map = $this->json_manager->write(
                __DIR__ . '\..\..\db\MapDB.json',
                $map->getMap()
            );

            if ($produced_player === false || $produced_map === false) {
                http_response_code(500);
                echo json_encode(['error' => 'Internal server error. Something went wrong.']);
                exit;
            }

            echo json_encode(['points' => $player->getPoints(), 'position' => $player->getPosition()]);

            exit;
        }

        http_response_code(400);
        echo json_encode(['error' => "You can't move to room $room_number"]);
    }

    public function minPath(): array
    {
        $consumed_map = $this->json_manager->read(__DIR__ . '\..\..\db\MapDB.json');
        $map = new Map($consumed_map);

        http_response_code(200);
        return $map->findMinPath();
    }
}
