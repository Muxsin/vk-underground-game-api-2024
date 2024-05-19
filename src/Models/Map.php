<?php

declare(strict_types=1);

namespace Muhsin\Vk\Models;

class Map
{
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function getStartingRoom(): int
    {
        return $this->map['enter_room'];
    }

    public function getExitRoom(): int
    {
        return $this->map['exit_room'];
    }

    public function getRooms(): array
    {
        return $this->map['rooms'];
    }

    public function setRooms(array $rooms): self
    {
        $this->map['rooms'] = $rooms;

        return $this;
    }

    public function getRoomByNumber(int $room_number): ?array
    {
        foreach ($this->getRooms() as $room) {
            if ($room['room_number'] === $room_number) {
                return $room;
            }
        }

        return null;
    }

    public function getConnectedRooms(int $room_number): array
    {
        return $this->getRoomByNumber($room_number)['connected_rooms'];
    }

    public function canMoveToRoom(int $from_room_number, int $to_room_number): bool
    {
        if (in_array($to_room_number, $this->getConnectedRooms($from_room_number), true)) {
            return true;
        }

        return false;
    }

    public function makeVisited(int $room_number): void
    {
        $rooms = $this->getRooms();

        for ($i = 0; $i < count($rooms); $i++) {
            if ($rooms[$i]['room_number'] === $room_number) {
                $rooms[$i]['visited'] = true;
                break;
            }
        }

        $this->setRooms($rooms);
    }

    public function findMinPath()
    {
        $starting_room = $this->getStartingRoom();
        $to_visit = [$starting_room];
        $visited = [];
        $distances = [
            $starting_room => 0,
        ];

        while ($to_visit) {
            $current = array_shift($to_visit);
            $visited[$current] = true;

            foreach ($this->getConnectedRooms($current) as $connected_room) {
                $distance = min($distances[$connected_room] ?? PHP_INT_MAX, $distances[$current] + 1);
                $distances[$connected_room] = $distance;

                if (!isset($visited[$connected_room]) && !in_array($connected_room, $to_visit, true)) {
                    $to_visit[] = $connected_room;
                }
            }
        }

        $exit_room = $this->getExitRoom();
        $path = [];

        $current = $exit_room;

        while ($current !== $starting_room) {
            $path[] = $current;

            foreach ($this->getConnectedRooms($current) as $connected_room) {
                if ($distances[$connected_room] + 1 === $distances[$current]) {
                    $current = $connected_room;
                    break;
                }
            }
        }

        $path[] = $starting_room;

        return array_reverse($path);
    }
}
