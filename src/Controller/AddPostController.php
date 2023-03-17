<?php

declare(strict_types=1);

namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\{InvalidInputsException,PostInputValidator};
use BlogPostsHandling\Api\Entity\{Post,FileUploaded};
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddPostController
{
    /**
     * @OA\Post(
     *     path="/v1/new/post",
     *     @OA\Response(response="201", description="Adding a new post to the blog route")
     * )
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();

        try {
            if (isset($inputs['thumbnail'])) {
                $thumbnail = new FileUploaded($inputs['thumbnail']);
                $inputs['thumbnail'] = $thumbnail;
            }
            $postInputValidator = new PostInputValidator($inputs);
            $postInputValidator
                ->defaultValidation()
                ->sendResult();
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('new post not created, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        }

        $post = Post::createFromArrayAssoc($inputs);

        try {
            if (
                $postRepository->store($post)  &&
                (isset($thumbnail) && $thumbnail->store(__DIR__ . '/../../public/') )
            ) {
                return $responseHandler
                ->type('/v1/post_added')
                ->title('post_added')
                ->status(201)
                ->detail('post [' . $post->title() . '] successfully added')
                ->jsonSend(["post" => $post->toMap()]);
            } else {
                throw new \Error('post/thumbnail object cannot be stored');
            }
        } catch (\Throwable $th) {
            error_log('Error occurred -> ' .
                "File: {$th->getFile()}:{$th->getLine()}, message: {$th->getMessage()}" . PHP_EOL);
            return $responseHandler
                ->type('/v1/errors/post_not_added')
                ->title('post_not_added')
                ->status(500)
                ->detail('a new post cannot be added due to a server error')
                ->jsonSend();
        }
    }
}
