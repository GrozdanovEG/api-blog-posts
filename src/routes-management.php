<?php
declare(strict_types=1);

use BlogPostsHandling\Api\Controller\HomeController;
use BlogPostsHandling\Api\Controller\AddPostController;
use BlogPostsHandling\Api\Controller\GetPostController;
use BlogPostsHandling\Api\Controller\UpdatePostController;
use BlogPostsHandling\Api\Controller\DeletePostController;

use BlogPostsHandling\Api\Controller\AddCategoryController;
use BlogPostsHandling\Api\Controller\GetCategoryController;
use BlogPostsHandling\Api\Controller\UpdateCategoryController;
use BlogPostsHandling\Api\Controller\DeleteCategoryController;
use BlogPostsHandling\Api\Controller\ListCategoriesController;

use BlogPostsHandling\Api\Controller\GetPostsBySlugController;
use BlogPostsHandling\Api\Controller\AddCategoryToAPostController;

use BlogPostsHandling\Api\Controller\OpenApiDocController;

/* Application routes  */
$app->get('/v1', HomeController::class);

$app->post('/v1/new/category', AddCategoryController::class);

$app->get('/v1/read/category/{id}', GetCategoryController::class);

$app->put('/v1/update/category/{id}', UpdateCategoryController::class);

$app->get('/v1/list/categories', ListCategoriesController::class);

$app->delete('/v1/delete/category/{id}', DeleteCategoryController::class);

$app->post('/v1/new/post', AddPostController::class);

$app->get('/v1/read/post/{id}', GetPostController::class);

$app->put('/v1/update/post/{id}', UpdatePostController::class);

$app->delete('/v1/delete/post/{id}', DeletePostController::class);

$app->get('/v1/posts/slug/{slug}', GetPostsBySlugController::class);

$app->post('/v1/post/{pid}/addto/{cid}', AddCategoryToAPostController::class);

$app->get('/v1/apidocs', OpenApiDocController::class);
/* Application routes ends */

