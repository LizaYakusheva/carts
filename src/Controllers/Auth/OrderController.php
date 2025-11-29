<?php

namespace Src\Controllers\Auth;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Controllers\Controller;
use Src\Services\CartServices;
use YooKassa\Client;

class OrderController extends Controller
{
    public function __construct(PhpRenderer $renderer, protected CartServices $cartServices, protected Client $client)
    {
        parent::__construct($renderer);
    }

    public function success(RequestInterface $request, ResponseInterface $response, array $args)
    {
//        $order = ORM::forTable('orders')->findOne($args['id'])->set(['status' => '']);

        return $this->renderer->render($response, 'success.php');
    }

    public function store(RequestInterface $request, ResponseInterface $response)
    {
        $orderSum = 0;

        $userId = $_SESSION['user_id'];
        $cartId = $this->cartServices->getCartId();
        $cartItems = $this->cartServices->getCartItems();

        foreach ($cartItems as $cartItem){
            $product = ORM::forTable('products')->findOne($cartItem['product_id']);

            $currentCartItem = ORM::forTable('cart_items')->findOne($cartItem['id'])->set([
                'price' => $product['price']
            ]);
            $currentCartItem->save();

            $orderSum += $currentCartItem['price'] * $cartItem['count'];
        }

        $order = ORM::forTable('orders')->create([
            'user_id' => $userId,
            'cart_id' => $cartId,
        ]);

        $order->save();

        $paymentResponse = $this->client->createPayment(
            [
                'amount' => [
                    'value' => $orderSum,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'locale' => 'ru_RU',
                    'return_url' => 'http://localhost/payment/' . $order->id,
                ],
                'capture' => true,
                'description' => 'Заказ ' . $order->id,
                'metadata' => [
                    'orderNumber' => $order->id
                ],
            ]
        );

        $paymentId = $paymentResponse->getId();
        $paymentLink = $paymentResponse->confirmation->getConfirmationUrl();
        $status = $paymentResponse->getStatus();

//        $a = $client->getPaymentInfo($paymentId);
//        $a->getStatus();

        ORM::forTable('orders')->findOne($order->id)
            ->set([
                'order_id' => $paymentId,
                'payment_link' => $paymentLink,
                'status' => $status,
            ])
        ->save();

        ORM::forTable('carts')->findOne($cartId)->set([
            'status' => 'closed'
        ])->save();

        setcookie('cart_id', $cartId, time() - 60 * 60 * 24 * 31, '/');

        return $response->withHeader('Location', $paymentLink)->withStatus(302);
    }

    public function index(RequestInterface $request, ResponseInterface $response)
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
            $orderItemsGrouped[$orderItem['cart_id']][] = $orderItem;
        }

        return $this->renderer->render($response, 'orders.php', [
           'orders' => $orders,
           'orderItemsGrouped' => $orderItemsGrouped,
        ]);
    }

}