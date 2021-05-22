<?php

declare(strict_types=1);

namespace controllers\presentation;

class LogoutPresentationController extends PresentationController
{
    public function index(): void
    {
        \models\Login::verifyLogout();
    }
}
