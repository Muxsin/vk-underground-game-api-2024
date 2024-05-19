<?php

declare(strict_types=1);

namespace Muhsin\Vk\Normalizers;

class MovementNormalizer implements NormalizerInterface
{
    public function normalize(array $request): int
    {
        return $request['room_number'];
    }
}
