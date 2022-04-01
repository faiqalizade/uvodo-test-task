<?php

namespace Core\Facades\DataSource;

use Core\Facades\Facade;
use Core\Model;

/**
 * @method static bool delete()
 * @method static Model save(Model $model)
*/
class DataSource extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DataSourceManager::class;
    }
}
