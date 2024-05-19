<?php

declare(strict_types=1);

namespace Muhsin\Vk\Normalizers;

use Muhsin\Vk\Models\Map;

class MapNormalizer implements NormalizerInterface
{
    public function normalize(array $request): Map
    {
        $normalized_map = [
            'enter_room' => $request['enter_room'],
            'exit_room' => $request['exit_room'],
            'rooms' => $request['rooms'],
        ];

        return new Map($normalized_map);
    }
}
