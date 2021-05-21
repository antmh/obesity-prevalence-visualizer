<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\Controller;

class LogoutController extends Controller
{
    public function index(): void
    {
        \models\Login::verifyLogout();
    }
}
