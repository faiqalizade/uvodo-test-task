<?php
namespace Core\Facades\DataSource;

class DataSourceManager
{
    protected Source $source;

    public function __construct()
    {
        $this->setSource();
    }

    protected function setSource()
    {
        $app = Source::getApplication();;
        $sourceName = $app::$config['data_source'];
        $source = $app::$config['data_sources'][$sourceName];

        $this->source = new $source;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->source->$name(...$arguments);
    }

}
