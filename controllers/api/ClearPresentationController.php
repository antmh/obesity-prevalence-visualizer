<?php

declare(strict_types=1);

namespace controllers\api;

class ClearPresentationController
{
    public function index(): void
    {
        \views\View::render('clear.php');
    }
}
