<?php

namespace Src\Controllers\Auth;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Controllers\Controller;
use Src\Services\CartServices;

class OrderController extends Controller
{
    public function __construct(PhpRenderer $renderer, protected CartServices $cartServices)
    {
        parent::__construct($renderer);
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $userId = $_SESSION['user_id'];
        $cartId = $this->cartServices->getCartId();
        $cartItems = $this->cartServices->getCartItems();

        foreach ($cartItems as $cartItem){
            $product = ORM::forTable('products')->findOne($cartItem['product_id']);

            ORM::forTable('cart_items')->findOne($cartItem['id'])->set([
                'price' => $product['price']
            ])->save();
        }

        ORM::forTable('orders')->create([
            'user_id' => $userId,
            'cart_id' => $cartId,
        ])->save();

        ORM::forTable('carts')->findOne($cartId)->set([
            'status' => 'closed'
        ])->save();

        setcookie('cart_id', $cartId, time() - 60 * 60 * 24 * 31, '/');

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function store(RequestInterface $request, ResponseInterface $response)
    {
        $orders = ORM::forTable('orders')
            ->where('user_id', $_SESSION['user_id'])
            ->orderByDesc('id')
            ->findMany();
        $cartIds = array_column($orders, 'cart_id');
        $orderItems = ORM::forTable('cart_items')
            ->select('cart_items.*')
            ->select('products.name', 'product_name')
            ->join('products', ['products.id', '=' , 'cart_items.product_id'])
            ->whereIn('cart_id', $cartIds)
            ->findArray();
        $orderItemsGrouped = [];

        foreach ($orderItems as $orderItem){
            $orderItemsGrouped[$orderItem['cart_id']] [] = $orderItem;
        }

        return $this->renderer->render($response, 'orders.php', [
           'orders' => $orders,
           'orderItemsGrouped' => $orderItemsGrouped,
        ]);
    }

}