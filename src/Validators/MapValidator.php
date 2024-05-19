<?php

declare(strict_types=1);

namespace Muhsin\Vk\Validators;

class MapValidator implements ValidatorInterface
{
    public function validate(array $request): bool
    {
        if(
            !key_exists('enter_room', $request)
            || !key_exists('exit_room', $request)
            || !key_exists('rooms', $request)
        ) {
            return false;
        }

        foreach ($request['rooms'] as $room) {
            if (
                !key_exists('room_number', $room)
                || !key_exists('type', $room)
                || !key_exists('connected_rooms', $room)
                || !key_exists('visited', $room)
            ) {
                return false;
            }
        }

        return true;
    }
}
