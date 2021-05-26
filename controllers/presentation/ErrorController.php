<?php

declare(strict_types=1);

namespace controllers\presentation;

use views\View;

class ErrorController
{
    public function index(string $message): void
    {
        View::render('error.php', [ 'message' => $message ]);
    }
}
