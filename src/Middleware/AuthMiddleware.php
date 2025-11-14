<?php

namespace Src\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class AuthMiddleware
{
    public function __construct(protected ResponseFactory $responseFactory)
    {
    }

    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler)
    {
        if(!isset($_SESSION['user_id'])){
            $response = $this
                ->responseFactory
                ->createResponse();

            return $response
                ->withHeader('Location','/login')
                ->withStatus(302);
        }

        return $handler->handle($request);
    }
}