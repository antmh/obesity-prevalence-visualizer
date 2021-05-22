<?php

declare(strict_types=1);

namespace controllers\presentation;

class AdministrationPresentationController extends PresentationController
{
    public function index(): void
    {
        \views\View::render('administration/administration.php');
    }
}
