<?php

declare(strict_types=1);

namespace Muhsin\Vk\Models;

class Player
{
    private int $points;
    private int $position;

    public function __construct(int $points, int $position)
    {
        $this->points = $points;
        $this->position = $position;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function increasePoint(int $point): self
    {
        $this->points += $point;

        return $this;
    }
}
