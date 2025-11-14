<?php

namespace Src\Controllers;

use ORM;
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
    public function index(RequestInterface $request, ResponseInterface $response)
    {
//        $cartItems = $this->cartServices->getCartItems();

        $cartItems = ORM::forTable('cart_items')
            ->select('cart_items.*')
            ->select('products.name', 'product_name')
            ->join('products', 'products.id = cart_items.product_id')
            ->where('cart_id', $_COOKIE['cart_id'])
            ->findArray();

        return $this->renderer->render($response, 'cart.php',[
            'cartItems' => $cartItems,
        ]);
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

    public function addCart(RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        $this->cartServices->add($productId);

        return $response->withHeader('Location', '/cart')->withStatus(302);
    }

    public function minusCart(RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        $this->cartServices->minus($productId);

        return $response->withHeader('Location', '/cart')->withStatus(302);
    }
}