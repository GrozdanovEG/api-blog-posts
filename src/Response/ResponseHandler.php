<?php
declare(strict_types=1);
namespace BlogPostsHandling\Api\Response;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResponseHandler
{
    protected string $type;
    protected string $title;
    protected int $status;
    protected string $detail;
    protected string $instance;

    public function __construct()
    {
        $this->status = 200;
        $this->instance = $_SERVER['PATH_INFO'];
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function status(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function detail(string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    public function instance(string $instance): self
    {
        $this->instance = $instance;
        return $this;
    }

    public function jsonSend(mixed $data = null): JsonResponse
    {
        $responseData = ['status' => $this->status];
        if (isset($data)) $responseData['data'] = $data;
        if (isset($this->type)) $responseData['type'] = $this->type;
        if (isset($this->title)) $responseData['title'] = $this->title;
        if (isset($this->detail)) $responseData['detail'] = $this->detail;
        if (isset($this->instance)) $responseData['instance'] = $this->instance;

        return new JsonResponse($responseData, $this->status);
    }
}

/*
$rh = new ResponseHandler();
$rh->type('')
    ->title('')
->status()
->detail('')
    ->instance('')
    ->jsonSend();
*/