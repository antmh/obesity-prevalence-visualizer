<?php

declare(strict_types=1);

namespace controllers\api;

use models\database\ {
    Database,
    EurostatRepository,
};

class EurostatController extends DataController
{
    protected function getRepository(): EurostatRepository
    {
        return Database::getInstance()->getEurostatRepository();
    }
}
