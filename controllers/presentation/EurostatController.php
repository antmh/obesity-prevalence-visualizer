<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\presentation\StatisticsController;
use models\database\ {
    Database,
    EurostatRepository,
};

class EurostatController extends StatisticsController
{
    protected function getRepository(): EurostatRepository
    {
        return Database::getInstance()->getEurostatRepository();
    }
}
