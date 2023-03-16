<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\InvalidInputsException;
use BlogPostsHandling\Api\Validator\PostInputValidator;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;

class DeletePostController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['id'] = $args['id'] ?? $inputs['id'] ?? null;
        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();

        $validRequest = isset($inputs['id']);
        $post = null;

        if($validRequest)
        try{
            $postInputValidator = new PostInputValidator($inputs);
            $postInputValidator->minimalValidation()->sendResult();
            $post = $postRepository->findById( $inputs['id'] );
        } catch (NotFoundException $nfe) {
            return $responseHandler
                ->type('/v1/errors/post_id_not_found')
                ->title('post_id_not_found')
                ->status(404)
                ->detail($nfe->getMessage() . 'Nothing to be deleted. ')
                ->jsonSend();
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('the post ['.$inputs['id'].'] was not deleted, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        }

        if (($post instanceof Post) &&
             $postRepository->deleteById($post->id()) )
            return $responseHandler
                ->type('/v1/post_deleted')
                ->title('Post deleted')
                ->status(200)
                ->detail('post ['.$post->title().'] was successfully deleted')
                ->jsonSend(["post" => $post->toMap()]);


        return $responseHandler
            ->type('/v1/errors/post_deletion_failure')
            ->title('post_not_deleted')
            ->status(500)
            ->detail('the post ['.$post->title().'] was not deleted for unknown reason')
            ->jsonSend(["msgid" => 'post_deletion_failure']);
    }
}