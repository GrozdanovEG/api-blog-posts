<?php
declare(strict_types=1);

use BlogPostsHandling\Api\Controller\HomeController;
use BlogPostsHandling\Api\Controller\AddPostController;

use BlogPostsHandling\Api\Controller\OpenApiDocController;

/* Application routes  */
$app->get('/', HomeController::class);

$app->post('/v1/new/post', AddPostController::class);

$app->get('/v1/read/post/{id}', GetPostController::class);

$app->put('/v1/update/post/{id}', UpdatePostController::class);

$app->delete('/v1/delete/post/{id}', DeletePostController::class); // 4

$app->post('/v1/new/category', AddCategoryController::class);

$app->get('/v1/read/category/{id}', GetCategoryController::class);

$app->put('/v1/update/category/{id}', UpdateCategoryController::class);

$app->delete('/v1/delete/category/{id}', DeleteCategoryController::class);  // 8

$app->get('/v1/posts/slug/{slug}', GetPostsBySlugController::class);

$app->put('/v1/post/{pid}/addto/{cid}', AddCategoryToAPostController::class);
/*This route must add categories to a post */

$app->get('/v1/apidocs', OpenApiDocController::class);
/* Application routes ends */

