<?php

declare(strict_types=1);

namespace Muhsin\Vk\Validators;

class MovementValidator implements ValidatorInterface
{

    public function validate(array $request): bool
    {
        if(!key_exists('room_number', $request)) {
            return false;
        }

        return true;
    }
}
