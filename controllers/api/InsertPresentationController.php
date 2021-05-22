<?php

declare(strict_types=1);

namespace controllers\api;

class InsertPresentationController
{
    public function index(): void
    {
        \views\View::render('insert.php');
    }
}
