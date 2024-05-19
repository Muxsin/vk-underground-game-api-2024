<?php

namespace Muhsin\Vk\Managers;

class JsonManager implements FileManager
{

    public function read(string $dir): array|bool
    {
        if (!is_dir(dirname($dir))) {
            return false;
        }

        $contents = file_get_contents($dir);

        if ($contents === false) {
            return false;
        }

        $data = json_decode($contents, true);

        if ($data === null) {
            return false;
        }

        return $data;
    }

    public function write(string $dir, array $content): bool
    {
        if (!is_dir(dirname($dir))) {
            return false;
        }

        $result = file_put_contents($dir, json_encode($content));

        if ($result === false) {
            return false;
        }

        return true;
    }
}