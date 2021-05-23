<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\Authentication;
use core\PresentationException;

class ProcessLoginController
{
    public function post(): void
    {
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            throw new PresentationException('Invalid input', 400);
            return;
        }
        $token = Authentication::getToken($_POST['username'], $_POST['password']);
        if ($token === null) {
            throw new PresentationException('Invalid credentials', 401);
            return;
        }
        setcookie('token', $token->getContent(), $token->getExpiration()->getTimestamp());
        header('Location: administration');
    }
}
