<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Мои заказы</h1>
<?php foreach ($orders as $order):?>
<div class="orders">
    <p>Заказ <?=$order['id']?></p>
    <table>
        <thead>
        <tr>
            <td>Товар</td>
            <td>Цена</td>
            <td>Количество</td>
            <td>Стоимость</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ordersItemsGrouped[$order['cart_id']] as $orderItem):?>
        <tr>
            <td><?=$orderItem['product_name']?></td>
            <td><?=$orderItem['price']?></td>
            <td><?=$orderItem['count']?></td>
            <td><?=$orderItem['count'] * $orderItem['price']?></td>
        </tr>
        </tbody>
    </table>
</div>
<?php endforeach;?>
</body>
</html>