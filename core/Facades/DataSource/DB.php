<?php
namespace  Core\Facades\DataSource;


use Core\Database;
use Core\Model;
use RedBeanPHP\OODBBean;

class DB extends Source
{
    protected Database $db;
    public function __construct()
    {
        global $app;
        $config = $app::$config['source_config']['db'];
        $this->db = Database::connect(...$config);
    }

    public function getAll(string|Model $class): array
    {
        $beans = \R::findAll($class::$tableName);
        $result = [];
        if ($beans) {
            foreach ($beans as $bean) {
                $model = self::setPropertiesToModel($class, $bean->getProperties(), $bean);
                $result[] =  $model;
            }
        }

        return $result;
    }


    public function findBy(string|Model $class, array $query): ?Model
    {
        // Todo Improve query Builder

        if (count($query) == 1) {
            $sql = array_keys($query)[0]." = ?";
            $bindings = array_values($query);
        }

        $result = \R::findOne($class::$tableName, $sql, $bindings);
        if (!$result) return null;
        $model = self::setPropertiesToModel($class, $result->getProperties());
        $model->setBean($result);
        return $model;
    }

    public function find(string|Model $class, int|string $id): ?Model
    {
        $result = \R::load($class::$tableName, $id);
        if ($result->id == 0) return null;

        $model = self::setPropertiesToModel($class, $result->getProperties());
        $model->setBean($result);
        return $model;
    }

    public function delete(Model|array $model): bool
    {
        if (is_array($model)) {
            \R::trashAll($model);
            return true;
        }

        if (!$model->getBean()) {
            return false;
        }

        \R::trash($model->getBean());

        return true;
    }


    public function save(Model $model): Model
    {
        $bean = $model->getBean();
        $isNew = false;
        if (!$bean) {
            $isNew = true;
            $bean = \R::dispense($model::$tableName);
        }

        $id = \R::store(self::setModelPropertiesToBean($model, $bean));

        if ($isNew) {
            $model->id = $id;
        }
        unset($bean);

        return $model;
    }

    protected static function setModelPropertiesToBean(Model $model, OODBBean $bean): OODBBean
    {
        $vars = $model->getProperties();
        foreach ($vars as $name => $value) {
            if (in_array($name, ['fillable', 'bean'])) continue;

            if (in_array($name, array_keys($vars['fillable']))) {
                $bean->$name = $value;
            }

        }

        return $bean;
    }
}
