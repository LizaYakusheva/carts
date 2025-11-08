<?php

namespace Src\Controllers;

use Slim\Views\PhpRenderer;

class Controller
{
    public function __construct(
        protected PhpRenderer $renderer,
    )
    {
    }
}