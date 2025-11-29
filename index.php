<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\Auth\LoginController;
use Src\Controllers\Auth\OrderController;
use Src\Controllers\Auth\RegisterController;
use Src\Controllers\CartController;
use Src\Controllers\HomeController;
use Src\Controllers\ProductController;
use Src\Middleware\AuthMiddleware;
use YooKassa\Client;

require __DIR__ . '/vendor/autoload.php';

session_start();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(Client::class, function (){
    $client = new Client();
    $client->setAuth('1218090', 'test_oAE61eVuKNisyXhiwzmjb8xVemFU8gQ5_9WAFDxcR_o');
    return $client;
});

$container->set(PhpRenderer::class, function () {
    return new PhpRenderer(__DIR__ . '/templates');
});

ORM::configure('mysql:host=database;dbname=docker; charset=utf8mb4');
ORM::configure('username', 'root');
ORM::configure('password', 'tiger');

$app->get('/login', [LoginController::class, 'loginPage']);
$app->post('/login', [LoginController::class, 'login']);
$app->get('/register', [RegisterController::class, 'registerPage']);
$app->post('/register', [RegisterController::class, 'register']);

$app->get('/', [HomeController::class, 'index']);
$app->get('/cart', [CartController::class, 'index']);
$app->post('/add', [CartController::class, 'add']);
$app->post('/minus', [CartController::class, 'minus']);
$app->post('/cart/add', [CartController::class, 'addCart']);
$app->post('/cart/minus', [CartController::class, 'minusCart']);
$app->get('/product/{id}', [ProductController::class, 'show']);

$app->group('/', function () use ($app){
    $app->get('/payment/{id}', [OrderController::class, 'success']);

    $app->post('/order', [OrderController::class, 'store']);
    $app->get('/order', [OrderController::class, 'index']);

    $app->get('/logout', [LoginController::class, 'logout']);
})->add(new AuthMiddleware($container->get(ResponseFactory::class)));
$app->run();
