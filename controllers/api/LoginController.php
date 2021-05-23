<?php

declare(strict_types=1);

namespace controllers\api;

use models\Authentication;
use core\ApiException;
use function json_encode;

class LoginController
{
    public function post(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'));
        if ($data === null || !isset($data->username) || !isset($data->password)) {
            throw new ApiException('Invalid input', 400);
            return;
        }
        $token = Authentication::getToken($data->username, $data->password);
        if ($token === null) {
            throw new ApiException('Invalid credentials', 401);
            return;
        }
        echo json_encode([
            'token' => $token->getContent(),
            'expires' => $token->getExpiration()->format(\DateTime::RFC7231),
        ]);
    }
}
