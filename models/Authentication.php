<?php

declare(strict_types=1);

namespace models;

use models\database\Database;
use Firebase\JWT\JWT;

class Authentication
{
    public static function getToken(string $username, string $password): ?string
    {
        if (!Database::getInstance()->adminCredentialsValid($username, $password)) {
            return null;
        }
        $secretKey = $_ENV['SECRET_KEY'];
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify('+10 minutes');
        $issuer = $_SERVER['SERVER_NAME'];
        return JWT::encode(
            [
                'iat' => $issuedAt->getTimestamp(),
                'iss' => $issuer,
                'nbf' => $issuedAt->getTimestamp(),
                'exp' => $expire->getTimestamp(),
            ],
            $secretKey,
            'HS512',
        );
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
