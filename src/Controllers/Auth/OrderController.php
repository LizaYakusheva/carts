<?php

namespace Src\Controllers\Auth;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Controllers\Controller;

class OrderController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $userId = $_SESSION['user_id'];
        $cartId = $_COOKIE['cart_id'];

        \ORM::forTable('orders')->create([
            'user_id' => $userId,
            'cart_id' => $cartId,
        ])->save();

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}