<?php

declare(strict_types=1);

namespace controllers\presentation;

class LogoutController extends PresentationController
{
    public function index(): void
    {
        \models\Login::verifyLogout();
    }
}
