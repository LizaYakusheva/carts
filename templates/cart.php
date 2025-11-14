<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<a href="/">Назад</a>
<h1>Корзина</h1>
<a href="/order">Заказать</a>
<table>
    <tr class="product-container">
        <?php foreach ($cartItems as $cartItem):?>
            <td class="product-item">
                <?=$cartItem['product_name']?>
                <a href="/product/<?=$cartItem['product_id']?>">Перейти</a>
                    <form action="/cart/minus" method="post">
                        <input type="hidden" name="product_id" value="<?=$cartItem['product_id']?>">
                        <input type="submit" value="-">
                    </form>
                    <span><?=$cartItem['count']?></span>
                    <form action="/cart/add" method="post">
                        <input type="hidden" name="product_id" value="<?=$cartItem['product_id']?>">
                        <input type="submit" value="+">
                    </form>
                    <form action="/cart/add" method="post">
                        <input type="hidden" name="product_id" value="<?=$cartItem['product_id']?>">
                    </form>
            </td>
        <?php endforeach;?>
    </tr>
</table>
</body>
</html>