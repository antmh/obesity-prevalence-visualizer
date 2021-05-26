<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\Authentication;
use core\PresentationException;

class AdministrationController
{
    public function index(): void
    {
        if (!Authentication::validate()) {
            throw new PresentationException('Authentication required', 401);
        }
        \views\View::render('administration.php');
    }
}
