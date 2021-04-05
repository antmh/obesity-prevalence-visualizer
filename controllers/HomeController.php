<?php

declare(strict_types=1);

namespace controllers;

class HomeController extends Controller
{
    public function index(): void
    {
        \views\View::render('home.php');
    }
}
