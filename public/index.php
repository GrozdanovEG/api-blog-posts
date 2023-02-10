<?php
use Slim\Factory\AppFactory;
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
require __DIR__ . '/../src/routes-management.php';

try {
    $app->run();
} catch (Exception $e) {
    error_log($e->getFile().':'.$e->getLine() . ' ['.$e->getMessage().']');
    echo 'Something went wrong!';
} catch (Throwable $th) {
    echo $th->getMessage();
}

