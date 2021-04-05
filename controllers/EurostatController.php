<?php

declare(strict_types=1);

namespace controllers;

class EurostatController extends Controller
{
    public function index(): void
    {
        $values = [43, 23, 12, 65, 89, 65, 23, 67, 23, 46, 54, 23];
        \views\View::render('eurostat.php', ['values' => $values]);
    }
}
