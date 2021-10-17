<?php

declare(strict_types=1);

namespace App\Controller;

use App\Container\ServiceContainer;
use App\Exception\NotFoundException;
use ReflectionException;

class DistanceController
{
    public function __construct(
        private ServiceContainer $container,
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function index(): string
    {
        return $this->container->get('DistanceService')->renderPhp('../View/distance.php');
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function addresses(array $addresses): string
    {
        return $this->container->get('DistanceService')->getCoordinates($addresses);
    }
}
