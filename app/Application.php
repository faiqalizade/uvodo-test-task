<?php
namespace App;

use Core\Facades\DataSource;
use Core\Request;
use Core\Route;

/**
 * Application class
 *
 */
class Application
{
    public static array $config;
    public function __construct()
    {
        Route::prefix('/api')->path("api.php");
        self::$config = require_once realpath("config/app.php");

        DataSource\Source::setApplication($this);
    }

    /**
     * @param $class
     * @return void
     */
    public function singleton($class)
    {
        $instance = new $class;
        $class::$instance = $instance;
    }

    public function run()
    {
        $route = Route::verify(Request::$instance->route(), Request::$instance->method);
        if (!$route) {
            die('404');
        }
        $queryParams = $route['queryParams'];
        $args = [];
        if (is_callable($route['handler'])) {
            $reflection = new \ReflectionFunction($route['handler']);
            $args = $this->assemblyArgs($reflection, $queryParams);
            $response = call_user_func_array($route['handler'], $args);
        }

        if (is_array($route['handler'])) {
            $controller = new $route['handler'][0]();
            $method = $route['handler'][1];
            $reflection = new \ReflectionMethod($route['handler'][0], $method);
            $args = $this->assemblyArgs($reflection, $queryParams);
            $response = call_user_func_array([$controller, $method], $args);
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($response['status'] ?? 200);
        echo json_encode($response['data'] ?? []);
    }

    private function assemblyArgs(\ReflectionMethod|\ReflectionFunction $reflection, array $queryParams)
    {
        $args = [];
        foreach ($reflection->getParameters() as $parameter) {
            if (class_exists($parameter->getType())) {
                // Todo this code means using just Request(Singleton) Classes in parameters
                $args[] = ((string) $parameter->getType())::$instance;
            } else {
                if (array_key_exists($paramName = $parameter->getName(), $queryParams)) {
                    $args[] = $queryParams[$paramName];
                }
            }

        }

        return $args;
    }
}
