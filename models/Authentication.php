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
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify('+10 minutes');
        return JWT::encode(
            [
                'iat' => $issuedAt->getTimestamp(),
                'iss' => self::getServerName(),
                'nbf' => $issuedAt->getTimestamp(),
                'exp' => $expire->getTimestamp(),
            ],
            self::getSecretKey(),
            'HS512',
        );
    }

    public static function validate(): bool
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return false;
        }
        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return false;
        }
        $token = $matches[1];
        if ($token === null) {
            return false;
        }
        try {
            $token = JWT::decode($token, self::getSecretKey(), ['HS512']);
        } catch (\Exception $e) {
            return false;
        }
        $now = new \DateTimeImmutable();
        if (
            $token->iss !== self::getServerName()
            || $token->nbf > $now->getTimestamp()
            || $token->exp < $now->getTimestamp()
        ) {
            return false;
        }
        return true;
    }

    private static function getSecretKey(): string
    {
        return $_ENV['SECRET_KEY'];
    }

    private static function getServerName(): string
    {
        return $_SERVER['SERVER_NAME'];
    }
}
