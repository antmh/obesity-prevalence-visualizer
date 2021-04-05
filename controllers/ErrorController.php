<?php

declare(strict_types=1);

namespace controllers;

use controllers\Controller;

class ErrorController extends Controller
{
    public function index(): void
    {
        \views\View::render('error.php');
    }
}
