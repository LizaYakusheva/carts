<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="/login">Войти</a>
<?php else: ?>
    <a href="/logout">Выйти</a>
<?php endif; ?>
<hr>
<h1>Список всех товаров</h1>
<a href="/cart">Корзина</a>
<a href="/order">Мои заказы</a>
<table>
    <tr class="product-container">
        <?php foreach ($products as $product):?>
            <td class="product-item">
                <h2><?=$product['name']?></h2>
                <p>Цена:<?=$product['price']?> руб.</p>
                <a href="/product/<?=$product['id']?>">Перейти</a>
                <?php if(array_key_exists($product['id'], $cartItems)):?>
                <form action="/minus" method="post">
                    <input type="hidden" name="product_id" value="<?=$product['id']?>">
                    <input type="submit" value="-">
                </form>
                <span><?=$cartItems[$product['id']]?></span>
                <form action="/add" method="post">
                    <input type="hidden" name="product_id" value="<?=$product['id']?>">
                    <input type="submit" value="+">
                </form>
                    <?php else:?>
                    <form action="/add" method="post">
                        <input type="hidden" name="product_id" value="<?=$product['id']?>">
                        <input type="submit" value="Добавить в корзину">
                    </form>
            <?php endif;?>
            </td>
        <?php endforeach;?>
    </tr>
</table>
</body>
</html>

