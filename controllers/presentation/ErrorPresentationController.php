<?php

declare(strict_types=1);

namespace controllers\presentation;

class ErrorPresentationController extends PresentationController
{
    public function index(): void
    {
        \views\View::render('error.php');
    }
}
