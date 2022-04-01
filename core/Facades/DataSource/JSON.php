<?php
namespace Core\Facades\DataSource;

use Core\Model;

class JSON extends Source
{
    protected string $path;
    public function __construct()
    {
        $this->path = static::$app::$config['source_config']['json']['filepath'];
    }

    public function getAll(Model|string $class): array
    {
        $this->setModel($class);
        if (!file_exists($this->path)) return [];

        return json_decode(file_get_contents($this->path), true) ?? [];
    }

    public function findBy(string $class, array $query): ?Model
    {
        $this->setModel($class);
        $all = $this->getAll($class);
        if (!$all) return null;
        foreach ($all as $id => $values) {
            if ($values[array_keys($query)[0]] === array_values($query)[0]) {
                return self::setPropertiesToModel($class, $values);
            }
        }

        return null;
    }

    public function find(string $class, string|int $id): ?Model
    {
        $this->setModel($class);
        $all = $this->getAll($class);
        if (!$all) return null;
        if (!array_key_exists($id, $all)) return null;

        return self::setPropertiesToModel($class, $all[$id]);
    }

    public function delete(array|Model $model): bool
    {
        $this->setModel($model);
        $all = $this->getAll($model);
        if (!$all) return true;
        if (array_key_exists($model->id, $all)) {
            unset($all[$model->id]);
            file_put_contents($this->path, json_encode($all));
        }
        return true;
    }

    public function save(Model $model): Model
    {
        $all = $this->getAll($model);
        $id = self::uuid_v4();
        if ($model->id) {
           $id =  $model->id;
        } else {
            $model->id = $id;
        }

        $all[$id] = $this->getDataForSave($model) + ['id' => $id];

        file_put_contents($this->path, json_encode($all));

        return $model;
    }

    protected function setModel(Model|string $class)
    {
        $this->path = static::$app::$config['source_config']['json']['filepath']."/".$class::$tableName.".json";
    }

    protected function getDataForSave(Model $model): array
    {
        $data = [];
        $vars = $model->getProperties();
        foreach ($vars as $name => $value) {
            if (in_array($name, ['fillable', 'bean'])) continue;

            if (in_array($name, array_keys($vars['fillable']))) {
                $data[$name] = $value;
            }

        }

        return $data;
    }

    protected static function uuid_v4()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
