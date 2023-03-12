<?php
declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Entity\{Post,FileUploaded};
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddPostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $responseHandler = new ResponseHandler();

        // @todo moving and improving the validation logic to a separated class/layer
        $validRequest = count($inputs) > 0;
        foreach (['title', 'author', 'content'] as $key)
            if ( !isset($inputs[$key]) || $inputs[$key] === '') $validRequest = false;

        if (isset($inputs['thumbnail'])) {
            $thumbnail = new FileUploaded($inputs['thumbnail']);
            $inputs['thumbnail'] = $thumbnail;
        }

        if(!$validRequest)
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('post_creation_failed')
                ->status(400)
                ->detail('a new post cannot be created, no sufficient input data provided')
                ->jsonSend();

        $post = Post::createFromArrayAssoc($inputs);

        if ( (new PostRepositoryByPdo())->store($post)  &&
            (isset($thumbnail) && $thumbnail->store(__DIR__.'/../../public/') )
        ) return $responseHandler
            ->type('/v1/post_added')
            ->title('post_added')
            ->status(201)
            ->detail('post ['.$post->title().'] successfully added')
            ->jsonSend(["post" => $post->toMap()]);

        else return $responseHandler
            ->type('/v1/errors/post_not_added')
            ->title('post_not_added')
            ->status(500)
            ->detail('a new post cannot be added due to a server error')
            ->jsonSend();
    }
}
