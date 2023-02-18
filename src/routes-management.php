<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Application routes  */  // For now GET only
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Application root");
    return $response;
});

$app->get('/new/post', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must create a new post");
    return $response;
});

$app->get('/read/post/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must fetch a post by id");
    return $response;
});

$app->get('/update/post/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must update a post by id");
    return $response;
});

$app->get('/delete/post/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must delete a post by id");
    return $response;
}); // 4

$app->get('/new/category', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must create a new category");
    return $response;
});

$app->get('/read/category/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must fetch a category by id");
    return $response;
});

$app->get('/update/category/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must update a category by id");
    return $response;
});

$app->get('/delete/category/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must delete a category by id");
    return $response;
});  // 8

$app->get('/posts/slug/{slug}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must fetch post by given slug");
    return $response;
}); // 9

$app->get('/post/{pid}/add/{cid}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("This route must add categories to a post");
    return $response;
});  // 10
/* Application routes ends */
