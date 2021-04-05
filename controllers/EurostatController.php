<?php

declare(strict_types=1);

namespace controllers;

class EurostatController extends Controller
{
    public function index()
    {
        \views\View::render('eurostat.php');
    }
}
