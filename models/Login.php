<?php

declare(strict_types=1);

namespace models;

use controllers;

class Login
{
    public static function verifyLogin($email, $password)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = null;

        if ($email == "root@opvmail.com" && $password == "root") {
            $user = "ok";
        }

        if ($user != null) {
            $_SESSION['LOGGED'] = true;
            header('location: /');
            die();
        }

        if ($user == null) {
            $_SESSION['ERR'] = 'Wrong user or password.';
        }
    }

    public static function verifyLogout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['LOGGED'])) {
            unset($_SESSION['LOGGED']);
        }

        if (!isset($_SESSION['LOGGED'])) {
            header('Location: /');
            die();
        }
    }
}
