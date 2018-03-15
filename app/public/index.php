<?php
declare(strict_types=1);
require_once '../vendor/autoload.php';

use Bark\ServiceProvider;
use Slim\Container;

$container = new Container();
$container->register(new ServiceProvider());
$app = new \Slim\App($container);

include '../src/routes.php';

$app->run();
