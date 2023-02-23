<?php
declare(strict_types=1);
require_once __DIR__ . '/../common/appinit.php';

use Slim\Factory\AppFactory;

/* Loading and Running the application */
try {
    $c = (new BlogPostsHandling\Api\Factory\ContainerFactory)();
    AppFactory::setContainer($c);
    $app = AppFactory::create();

    $app->addErrorMiddleware(true, true, true);
    require_once __DIR__ . '/../src/routes-management.php';
    $app->run();

} catch (Exception $e) {
    error_log($e->getFile().':'.$e->getLine() . ' ['.$e->getMessage().']');
    echo 'Something went wrong!';

} catch (Throwable $th) {
    echo $th->getMessage();

}

