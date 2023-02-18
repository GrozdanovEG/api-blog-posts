<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

/* Loading the application */
try {
    $app = AppFactory::create();
    $app->addErrorMiddleware(false, true, true);
    require __DIR__ . '/../src/routes-management.php';
} catch (Throwable $th) {
    error_log($th->getFile().':'.$th->getLine() . ' ['.$th->getMessage().']');
    echo $th->getMessage();
}


/* Running the application */
try {
    $app->run();
} catch (Exception $e) {
    error_log($e->getFile().':'.$e->getLine() . ' ['.$e->getMessage().']');
    echo 'Something went wrong!';
} catch (Throwable $th) {
    echo $th->getMessage();
}

