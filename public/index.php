<?php
declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;
use BlogPostsHandling\Api\Storage\GetPdoConnection;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

use BlogPostsHandling\Api\Storage\StorageData;
use BlogPostsHandling\Api\Storage\DatabaseDataObject;
use BlogPostsHandling\Api\Storage\DatabaseConnection;
$dbdo = new DatabaseDataObject( (new StorageData($_ENV))->databaseData() );
$connection = (new DatabaseConnection($dbdo) )->connect();


/* Loading and Running the application */
try {
    $container = new Container();
    $container->set('PdoDbConn', $connection);
    //var_dump($container); exit;

    AppFactory::setContainer($container);
    $app = AppFactory::create();

    $app->addErrorMiddleware(true, true, true);
    require __DIR__ . '/../src/routes-management.php';
    $app->run();

} catch (Exception $e) {
    error_log($e->getFile().':'.$e->getLine() . ' ['.$e->getMessage().']');
    echo 'Something went wrong!';

} catch (Throwable $th) {
    echo $th->getMessage();

}

