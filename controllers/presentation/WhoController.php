<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\presentation\StatisticsController;
use models\ {
    database\Database,
    database\WhoRepository,
};

class WhoController extends StatisticsController
{
    protected function getRepository(): WhoRepository
    {
        return Database::getInstance()->getWhoRepository();
    }
}
