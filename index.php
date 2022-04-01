<?php
require_once './core/RedBeanPHP.php';
require_once "vendor/autoload.php";

$app = new App\Application();

$app->singleton(\Core\Request::class);
$app->singleton(\Core\Response::class);

$app->run();
