<?php
namespace Core;

use \R;

class Database
{
    public static ?Database $instance = null;

    public function connect(string $host, string $username, string $password, string $dbname, int $port)
    {
        R::setup("mysql:host={$host}:{$port};dbname={$dbname}", $username, $password);
    }
}
