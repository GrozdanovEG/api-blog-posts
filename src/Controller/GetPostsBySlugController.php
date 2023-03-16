<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Controller;

use BlogPostsHandling\Api\Entity\Post;
use BlogPostsHandling\Api\Repository\PostRepositoryByPdo;
use BlogPostsHandling\Api\Response\ResponseHandler;
use BlogPostsHandling\Api\Validator\InvalidInputsException;
use BlogPostsHandling\Api\Validator\PostInputValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetPostsBySlugController
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $inputs = json_decode($request->getBody()->getContents(), true);
        $inputs['slug'] = $args['slug'] ?? $inputs['slug'] ?? null;

        $responseHandler = new ResponseHandler();
        $postRepository = new PostRepositoryByPdo();
        $post = null;

        if ($inputs['slug'] !== null)
        try {
            $postInputValidator = new PostInputValidator($inputs);
            $postInputValidator->slugValidation()->sendResult();
            $post = $postRepository->findBySlug( $inputs['slug']  );
        } catch (\NotFoundException $nfe) {
            return $responseHandler
                ->type('/v1/errors/post_slug_not_found')
                ->title('invalid_post_slug')
                ->status(404)
                ->detail($nfe->getMessage() . 'Nothing to be retrieved. ')
                ->jsonSend();
        } catch (InvalidInputsException $iie) {
            return $responseHandler
                ->type('/v1/errors/wrong_input_data')
                ->title('wrong_input_data')
                ->status(400)
                ->detail('the post ['.$inputs['id'].'] was not updated, no sufficient or invalid input data provided')
                ->jsonSend($iie->getErrorMessages());
        }

        if ( $post instanceof Post) {
            return $responseHandler
                ->type('/v1/resource_found')
                ->title('post_found')
                ->status(200)
                ->detail('post ['.$post->title().'] with slug ['. $post->slug() .'] was successfully found')
                ->jsonSend(["post" => $post->toMap()]);
        }

        return $responseHandler
                ->type('/v1/errors/post_not_found')
                ->title('post_not_found')
                ->status(404)
                ->detail('A post with slug [' . $inputs['slug'] . '] was not found for unknown reason, nothing to be retrieved')
                ->jsonSend();
    }
}

