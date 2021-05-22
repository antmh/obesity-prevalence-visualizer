<?php

declare(strict_types=1);

namespace controllers\api;

use models\Authentication;

class LoginController
{
    public function post(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'));
        if ($data === null || !isset($data->username) || !isset($data->password)) {
            $this->invalidCredentials();
            return;
        }
        $token = Authentication::getToken($data->username, $data->password);
        if ($token === null) {
            $this->invalidCredentials();
            return;
        }
        echo json_encode(['token' => $token]);
    }

    private function invalidCredentials(): void
    {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid credentials']);
    }
}
