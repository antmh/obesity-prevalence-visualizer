<?php

declare(strict_types=1);

namespace controllers\api;

use models\database\ {
    Database,
    WhoRepository,
};

class WhoController extends DataController
{
    protected function getRepository(): WhoRepository
    {
        return Database::getInstance()->getWhoRepository();
    }
}
