<?php

declare(strict_types=1);

namespace controllers\presentation;

class ProcessLogoutController
{
    public function get(): void
    {
        setcookie('token', '', 1);
        header('Location: /');
    }
}
