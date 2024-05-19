<?php

declare(strict_types=1);

namespace Muhsin\Vk\Core;

class Request
{
    private array $body;

    public function __construct(array $body)
    {
        $this->body = $body;
    }

    public function all(): array
    {
        return $this->body;
    }
}
