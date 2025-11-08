<?php

namespace Src\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartServices;

class HomeController extends Controller
{

    public function __construct(PhpRenderer $renderer, protected CartServices $cartServices)
    {
        parent::__construct($renderer);
    }

    public function index(RequestInterface $request, ResponseInterface $response)
{
    $products = \ORM::forTable('products')->findArray();
    return $this->renderer->render($response, '/index.php',[
        'products' => $products,
        'cartItems' => $this->cartServices->getGroupedCartItems(),
    ]);
}
}