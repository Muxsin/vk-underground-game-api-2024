<?php

declare(strict_types=1);

namespace Muhsin\Vk\Validators;

interface ValidatorInterface
{
    public function validate(array $request): bool;
}
