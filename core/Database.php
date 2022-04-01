<?php
namespace Core;

use \R;

class Database
{
    public static ?Database $instance = null;
    public static string $test = 'asd';

    public static function connect(string $host, string $username, string $password, string $dbname, int $port): static
    {

        if (static::$instance) {
            return static::$instance;
        }

        R::setup("mysql:host={$host}:{$port};dbname={$dbname}", $username, $password);
        $instance = new static();
        $instance::$instance = $instance;
        return $instance;
    }
}
