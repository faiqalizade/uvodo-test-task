<?php
namespace Core;

class Request extends \stdClass
{
    public static ?Request $instance = null;
    public array $mergedInput;
    private string $path;
    public string $method;

    public function __construct()
    {
        $this->path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $this->method = $_SERVER['REQUEST_METHOD'];

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE) ?? [];
        $this->mergedInput = $_GET + $_POST + $input;
    }

    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->mergedInput)) {
            return null;
        }

        return $this->mergedInput[$name];
    }

    public function all(): array
    {
        return $this->mergedInput;
    }

    public function route()
    {
        return $this->path;
    }
}
