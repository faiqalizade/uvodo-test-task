<?php
namespace Core;
/**
 * @method static Route get(string $route, array|callable $handler)
 * @method static Route post(string $route, array|callable $handler)
 * @method static Route put(string $route, array|callable $handler)
 * @method static Route delete(string $route, array|callable $handler)
*/
class Route
{
    private static array $routes = [];
    private static string $prefix = '';

    public static function __callStatic(string $name, array $arguments)
    {
        self::addRoute(strtoupper($name), ...$arguments);
    }

    static private function addRoute(string $method, string $route, array|callable $handler)
    {
        self::$routes[$method][self::$prefix.$route] = [
            'route' => self::$prefix.$route,
            'handler' => $handler,
            'queryParams' => []
        ];
    }

    static function prefix(string $prefix): Route
    {
        self::$prefix = $prefix;
        return new static();
    }

    public function path(string $path): array
    {
        require_once realpath("routes/$path");
        return self::$routes;
    }

    public static function verify(string $path, string $method)
    {
        $routes = self::$routes[$method];
        if (array_key_exists($path, $routes) || array_key_exists($path."/", $routes)) {
            return $routes[$path];
        }

        $pathList = array_keys($routes);
        $queryParams = [];
        foreach ($pathList as $path) {
            if (preg_match("/:[A-Z0-9a-z]+/", $path)) {
                $pattern = preg_replace("/:[A-Z0-9a-z]+/", "[\S\s]+", $path);
                $pattern = str_replace("/", "\/", $pattern);
                $pattern = "/^$pattern$/";
                if (preg_match($pattern, Request::$instance->route())) {
                    $currentRoutePathArr = explode('/', Request::$instance->route());
                    $pathArr = explode('/', $path);
                    foreach ($pathArr as $index => $item) {
                        if ($item and $item[0] === ":") {
                            $queryParams[str_replace(":", '', $item)] = $currentRoutePathArr[$index];
                        }
                    }
                    $routes[$path]['queryParams'] = $queryParams;
                    return $routes[$path];
                }
            }
        }
        return false;
    }
}
