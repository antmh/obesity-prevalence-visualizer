<?php

declare(strict_types=1);

namespace controllers;

use controllers\Controller;

class AboutController extends Controller
{
    public function index(): void
    {
        \views\View::render('about.php');
    }
}
