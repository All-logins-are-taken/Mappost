<?php

declare(strict_types=1);

namespace App\Container;

interface ServiceContainerInterface
{
    public function get(string $id): ?object;

    public function has(string $id): bool|object;
}
