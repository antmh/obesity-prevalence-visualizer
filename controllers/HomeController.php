<?php

declare(strict_types=1);

namespace controllers;

class HomeController extends Controller
{
    public function index()
    {
        \views\View::render('home.php');
    }
}
