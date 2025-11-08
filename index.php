<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\CartController;
use Src\Controllers\CartServices;
use Src\Controllers\HomeController;
use Src\Controllers\ProductController;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(PhpRenderer::class, function () {
    return new PhpRenderer(__DIR__ . '/templates');
});

ORM::configure('mysql:host=database;dbname=docker; charset=utf8mb4');
ORM::configure('username', 'root');
ORM::configure('password', 'tiger');

$app->get('/', [HomeController::class, 'index']);
$app->get('/cart', [ProductController::class, 'index']);
$app->post('/cart/add', [CartController::class, 'add']);
$app->post('/cart/minus', [CartController::class, 'minus']);
$app->get('/product/{id}', [ProductController::class, 'show']);


$app->run();
