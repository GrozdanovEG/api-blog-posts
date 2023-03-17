<?php

declare(strict_types=1);

use BlogPostsHandling\Api\Controller\HomeController;
use BlogPostsHandling\Api\Controller\{AddPostController,GetPostController,
    UpdatePostController,DeletePostController};
use BlogPostsHandling\Api\Controller\GetPostsBySlugController;
use BlogPostsHandling\Api\Controller\{AddCategoryController,GetCategoryController,
    UpdateCategoryController,DeleteCategoryController};
use BlogPostsHandling\Api\Controller\{ListCategoriesController,AddCategoryToAPostController};
use BlogPostsHandling\Api\Controller\OpenApiDocController;

/* Application routes  */
if (isset($app)) /* added upon suggestion by PHPStan*/
{

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
}
/* Application routes ends */
