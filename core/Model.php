<?php
namespace Core;

use Core\Facades\DataSource\DataSource;
use RedBeanPHP\OODBBean;

/**
 * @method static array getAll()
 * @method static Model|null findBy(array $query)
 * @method static Model|null find(int $id)
*/

class Model
{
    public static string $tableName;
    protected ?OODBBean $bean;

    public static function __callStatic(string $name, array $arguments)
    {
        return DataSource::$name(static::class, ...$arguments);
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function __get(string $name)
    {
        return $this->$name ?? null;
    }

    public function delete(): bool
    {
        return DataSource::delete($this);
    }


    public function setBean(OODBBean $bean)
    {
        $this->bean = $bean;
    }

    public function getBean(): ?OODBBean
    {
        return $this->bean ?? null;
    }


    public function fill(array $properties)
    {
        foreach ($this->fillable as $item => $rules) {
            if (in_array('required', $rules)) {
                if (!$this->$item) {
                    if (!array_key_exists($item, $properties) || !$properties[$item]) {
                        die('required');
                    }
                }
            }
            if (array_key_exists($item, $properties)) {
                $this->$item = $properties[$item];
            }
        }
    }

    public function save(): Model
    {
        return DataSource::save($this);
    }

    public function getProperties()
    {
        return get_object_vars($this);
    }
}
