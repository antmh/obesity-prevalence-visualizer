<?php

declare(strict_types=1);

namespace controllers;

use controllers\Controller;

class ServicesController extends Controller
{
    public function index()
    {
        \views\View::render('services.php');
    }
}