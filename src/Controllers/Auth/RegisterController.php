<?php

namespace Src\Controllers\Auth;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RegisterController
{
    public function registerPage(RequestInterface $request, ResponseInterface $response)
    {
        return $this->renderer->render($response, '/auth/register.php');
    }

}