<?php

declare(strict_types=1);

namespace controllers\presentation;

class LoginController extends PresentationController
{
    public function index(): void
    {
        \views\View::render('login.php');
    }
}
