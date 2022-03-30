<?php
namespace Core;

use \R;
use RedBeanPHP\OODBBean;

class Model
{
    public static string $tableName;
    private static OODBBean $bean;
    private static mixed $result = null;

    public function __construct()
    {
        static::$bean = R::dispense(static::$tableName);
    }

    public function __set(string $name, $value): void
    {
        static::$bean->$name = $value;
    }

    public function save()
    {
        return R::store(static::$bean);
    }

    public function __get(string $name)
    {
        return static::$bean->$name;
    }

    public static function __callStatic($pluginName, $params)
    {
        if ($pluginName != 'trash') {
            array_unshift($params, static::$tableName);
        }
        $instance = new static();
        $result = R::$pluginName(...$params);
        if ($result instanceof OODBBean) {
            static::$bean = $result;
        } else {
            static::$result = $result;
        }
        return $instance;
    }

    public function getResult()
    {
        return static::$bean->id != 0 ? static::$bean : static::$result;
    }

    public function fill(array $properties)
    {
        foreach (static::$fillable as $item => $rules) {
            if (in_array('required', $rules)) {
                if (!static::$bean->$item) {
                    if (!array_key_exists($item, $properties) || !$properties[$item]) {
                        die('required');
                    }
                }
            }
            if (array_key_exists($item, $properties)) {
                static::$bean->$item = $properties[$item];
            }
        }
    }
}
