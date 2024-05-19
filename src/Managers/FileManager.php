<?php

namespace Muhsin\Vk\Managers;

interface FileManager
{
    public function read(string $dir): array|bool;
    public function write(string $dir, array $content): bool;
}
