<?php

declare(strict_types=1);

namespace Muhsin\Vk\Normalizers;

interface NormalizerInterface
{
    public function normalize(array $request);
}
