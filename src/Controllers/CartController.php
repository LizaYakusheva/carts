<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartServices;
use YooKassa\Client;

class CartController extends Controller
{
    public function __construct(PhpRenderer $renderer, protected CartServices $cartServices)
    {
        parent::__construct($renderer);
    }
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $cartItems = $this->cartServices->getCartItems();
        $cartId = $this->cartServices->getCartId();
        $sum = ORM::forTable('cart_items')
            ->join('products', ['cart_items.product_id', '=', 'products.id'])
            ->select_expr('SUM(products.price * cart_items.count)', 'sum')
            ->where('cart_items.cart_id', $cartId)
            ->findOne();

        return $this->renderer->render($response, 'cart.php',[
            'cartItems' => $cartItems,
            'sum' => $sum,
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