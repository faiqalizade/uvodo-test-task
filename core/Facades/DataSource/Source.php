<?php
namespace Core\Facades\DataSource;

use App\Application;
use Core\Model;
use RedBeanPHP\OODBBean;

abstract class Source
{
    protected static ?Application $app;
    abstract public function getAll(string $class): array;
    abstract public function findBy(string $class, array $query): ?Model;
    abstract public function find(string $class, int|string $id): ?Model;
    abstract public function delete(Model|array $model): bool;
    abstract public function save(Model $model): Model;

    public static function setApplication(Application $application)
    {
        static::$app = $application;
    }

    public static function getApplication(): Application
    {
        return static::$app;
    }


    protected static function setPropertiesToModel(string|Model $class, array $properties): Model
    {
        $model = new $class();

        foreach ($properties as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }
}
