<?php

namespace Src\Controllers\Auth;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Controllers\Controller;

class LoginController extends Controller
{
    public function loginPage(RequestInterface $request, ResponseInterface $response)
    {
        return $this->renderer->render($response, '/auth/login.php');
    }

    public function login(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $login = $request->getParsedBody()['login'];
        $password = $request->getParsedBody()['password'];

        $user = \ORM::forTable('users')->where('login', $login)->findOne();

        if(!$user){
            echo 'Такогоп пользователя не существует';
            exit();
        }

        if ($user['password'] == $password){
            $_SESSION['user_id'] = $user['id'];
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        if(!$user['password'] !== $password){
            echo 'Пароль неверный';
            exit();
        }
    }

    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        unset($_SESSION['user_id']);
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}