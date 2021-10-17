<?php

declare(strict_types=1);

namespace App\Container;

use App\Exception\NotFoundException;
use App\Http\HttpClient;
use App\Service\DistanceService;
use App\Service\DotEnvService;
use ReflectionClass;
use ReflectionException;

final class ServiceContainer implements ServiceContainerInterface
{
    private array $services = [];

    public function __construct(array $parameters)
    {
        $this->services['DotEnvService'] = fn() => new DotEnvService($parameters['path']);
        $this->services['DistanceService'] = fn() => new DistanceService($this);
        $this->services['HttpClient'] = fn() => new HttpClient();
    }

    /**
     * @throws NotFoundException|ReflectionException
     */
    public function get(string $id): ?object
    {
        $item = $this->resolve($id);
        if (!($item instanceof ReflectionClass)) {
            return $item;
        }
        return $this->getInstance($item);
    }

    public function has(string $id): bool|object
    {
        try {
            $item = $this->resolve($id);
        } catch (NotFoundException $e) {
            return false;
        }

        if ($item instanceof ReflectionClass) {
            return $item->isInstantiable();
        }

        return $item;
    }

    /**
     * @throws NotFoundException
     */
    private function resolve(string $id): object
    {
        try {
            $name = $id;
            if (isset($this->services[$id])) {
                $name = $this->services[$id];
                if (is_callable($name)) {
                    return $name();
                }
            }
            return (new ReflectionClass($name));
        } catch (ReflectionException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    private function getInstance(ReflectionClass $item): ?object
    {
        $constructor = $item->getConstructor();

        if (is_null($constructor) || $constructor->getNumberOfRequiredParameters() == 0) {
            return $item->newInstance();
        }
        $params = [];

        foreach ($constructor->getParameters() as $param) {
            if ($type = $param->getType()) {
                $params[] = $this->get($type->getName());
            }
        }

        return $item->newInstanceArgs($params);
    }
}
