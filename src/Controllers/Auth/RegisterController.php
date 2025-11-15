<?php

namespace Src\Controllers\Auth;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Src\Controllers\Controller;

class RegisterController extends Controller
{
    public function registerPage(RequestInterface $request, ResponseInterface $response)
    {
        return $this->renderer->render($response, '/auth/register.php');
    }

    public function register(RequestInterface $request, ResponseInterface $response)
    {
        \ORM::forTable('users')->create([
            'login' => $request->getParsedBody()['login'],
            'password' => md5($request->getParsedBody()['password']),
        ])->save();

        return $response->withHeader('Location', '/login')->withStatus(302);
    }

}