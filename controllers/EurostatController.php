<?php

declare(strict_types=1);

namespace controllers;

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

    protected function getView(): string
    {
        return 'eurostat.php';
    }
}
