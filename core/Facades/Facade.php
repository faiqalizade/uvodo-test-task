<?php

namespace Core\Facades;

use Exception;

class Facade
{

    protected static function getFacadeRoot()
    {
        return new (static::getFacadeAccessor());
    }


    /**
     * @throws Exception
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new Exception('A facade root has not been set.');
        }

        return $instance->$method(...$arguments);
    }
}
