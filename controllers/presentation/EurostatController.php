<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\presentation\StatisticsPresentationController;
use models\database\ {
    Database,
    EurostatRepository,
};

class EurostatController extends StatisticsPresentationController
{
    protected function getRepository(): EurostatRepository
    {
        return Database::getInstance()->getEurostatRepository();
    }
}
