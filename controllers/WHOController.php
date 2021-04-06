<?php

declare(strict_types=1);

namespace controllers;

use controllers\Controller;

class WHOController extends Controller
{
    public function index(): void
    {
        \views\View::render('who.php');
    }
}
