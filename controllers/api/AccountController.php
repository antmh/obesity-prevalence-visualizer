<?php

declare(strict_types=1);

namespace controllers\api;

use models\ {
    database\Database,
    Authentication,
};
use core\ApiException;

class AccountController
{
    public function isPasswordValid(): void
    {
        $this->validate();
        $data = json_decode(file_get_contents('php://input'));
        if ($data === null || !isset($data->password)) {
            throw new ApiException('Invalid body', 400);
        }
        $valid = Database::getInstance()->getCredentials()['password'] === $data->password;
        echo json_encode(['valid' => $valid]);
    }

    public function changeUsername(): void
    {
        $this->validate();
        $data = json_decode(file_get_contents('php://input'));
        if ($data === null || !isset($data->username)) {
            throw new ApiException('Invalid body', 400);
        }
        Database::getInstance()->changeUsername($data->username);
        echo json_encode(['message' => 'Username changed']);
    }

    public function changePassword(): void
    {
        $this->validate();
        $data = json_decode(file_get_contents('php://input'));
        if ($data === null || !isset($data->password)) {
            throw new ApiException('Invalid body', 400);
        }
        Database::getInstance()->changePassword($data->password);
        echo json_encode(['message' => 'Password changed']);
    }

    private function validate()
    {
        if (!Authentication::validate()) {
            throw new ApiException('Authentication required', 401);
        }
    }
}
