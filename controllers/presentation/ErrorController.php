<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\Controller;

class ErrorController
{
    public function index(string $message): void
    {
        \views\View::render('error.php',['message' => $message]);
    }
}
