<?php

namespace Src\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartServices;

class CartController extends Controller
{
    public function __construct(PhpRenderer $renderer, protected CartServices $cartServices)
    {
        parent::__construct($renderer);
    }

    public function add(RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        $this->cartServices->add($productId);

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function minus(RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        $this->cartServices->minus($productId);

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}