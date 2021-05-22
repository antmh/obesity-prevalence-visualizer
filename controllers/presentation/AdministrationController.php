<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\Authentication;
use core\PresentationException;

class AdministrationController extends PresentationController
{
    public function index(): void
    {
        if (!Authentication::validate()) {
            throw new PresentationException('Not logged in',401);
        }
        \views\View::render('administration/administration.php');
    }
}
