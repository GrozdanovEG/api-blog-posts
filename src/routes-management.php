<?php
declare(strict_types=1);

use BlogPostsHandling\Api\Controller\HomeController;
use BlogPostsHandling\Api\Controller\AddPostController;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/* Application routes  */  // For now GET only
$app->get('/', HomeController::class);

$app->post('/v1/new/post', AddPostController::class);

$app->get('/v1/read/post/{id}', GetPostController::class);

$app->put('/v1/update/post/{id}', UpdatePostController::class);

$app->delete('/v1/delete/post/{id}', DeletePostController::class); // 4

$app->post('/v1/new/category', AddCategoryController::class);

$app->get('/v1/read/category/{id}', GetCategoryController::class);

$app->put('/v1/update/category/{id}', UpdateCategoryController::class);

$app->delete('/v1/delete/category/{id}', DeleteCategoryController::class);  // 8

$app->get('/v1/posts/slug/{slug}', GetPostsBySlugController::class); // 9

$app->get('/v1/post/{pid}/add/{cid}', AddCategoryToPostsBController::class);
/*This route must add categories to a post */
/* Application routes ends */
