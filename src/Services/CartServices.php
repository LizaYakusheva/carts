<?php

namespace Src\Services;

use ORM;

class CartServices
{

    private const COOKIE_NAME = 'cart_id';

    public function add(int $productId): void
    {
        $cartId = $this->getCartId();
        $cartItem = \ORM::forTable('cart_items')->where(['product_id' => $productId, 'cart_id' => $cartId])->findOne();
        if ($cartItem === false) {
            \ORM::forTable('cart_items')->create([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'count' => 1
            ])->save();
        } else {
            $cartItem->set([
                'count' => $cartItem['count'] + 1,
            ])->save();
        }
    }

    public function minus(int $productId): void
    {
        $cartId = $this->getCartId();
        $cartItem = \ORM::forTable('cart_items')->where([
            'product_id' => $productId,
            'cart_id' => $cartId,
        ])->findOne();
        if (!$cartItem) {
            return;
        }
        if ($cartItem['count'] > 1) {
            $cartItem->set([
                'count' => $cartItem['count'] - 1,
            ])->save();
        } else {
            $cartItem->delete();
        }
    }

    public function getCartItems(): array
    {
        $cartId = $this->getCartId();
        return ORM::forTable('cart_items')
            ->select('cart_items.*')
            ->select('products.name', 'product_name')
            ->join('products', 'products.id = cart_items.product_id')
            ->where('cart_id', $cartId)
            ->findArray();
    }

    public function getGroupedCartItems(): array
    {
        $cartItems = $this->getCartItems();
        $result = [];

        foreach ($cartItems as $cartItem) {
            $result[$cartItem['product_id']] = $cartItem['count'];
        }
        return $result;
    }

    public function getCartId(): int
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (isset($userId)) {
            $currentCart = \ORM::forTable("carts")->where([
                'user_id' => $userId,
                'status' => 'active',
            ])->findOne();

            if ($currentCart) {
                return $currentCart['id'];
            }
        }

        if (isset($_COOKIE[self::COOKIE_NAME])) {
            return $_COOKIE[self::COOKIE_NAME];
        }

        $cart = \ORM::forTable('carts')->create([
            'user_id' => $userId,
        ]);
        $cart->save();

        setcookie(self::COOKIE_NAME, $cart->id, time() + 60 * 60 * 24 * 31, '/');

        return $cart->id;
    }
}