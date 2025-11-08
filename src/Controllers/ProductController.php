<?php

namespace Src\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartServices;

class ProductController extends Controller
{
    public function __construct(
        PhpRenderer $renderer,
        protected CartServices $cartServices,
    )
    {
        parent::__construct($renderer);
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $cartItems = $this->cartServices->getGroupedCartItems();
        $product = \ORM::forTable('products')->findMany();
        return $this->renderer->render($response, '/cart.php', [
            'product' => $product,
            'cartItems' => $cartItems,
        ]);
    }
    public function show(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $id = $args['id'];
        $product = \ORM::forTable('products')->findOne($id);
        return $this->renderer->render($response, '/show.php', [
            'product' => $product,
        ]);
    }
}