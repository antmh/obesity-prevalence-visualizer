<?php

declare(strict_types=1);

namespace controllers;

use controllers\Controller;

class AdministrationController extends Controller
{
    public function index(): void
    {
        \views\View::render('administration.php');
    }
}
