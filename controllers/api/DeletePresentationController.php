<?php

declare(strict_types=1);

namespace controllers\api;

class DeletePresentationController
{
    public function index(): void
    {
        \views\View::render('delete.php');
    }
}
