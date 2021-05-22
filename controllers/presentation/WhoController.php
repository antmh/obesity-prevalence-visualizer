<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\presentation\StatisticsPresentationController;
use models\ {
    database\Database,
    database\WhoRepository,
};

class WhoController extends StatisticsPresentationController
{
    protected function getRepository(): WhoRepository
    {
        return Database::getInstance()->getWhoRepository();
    }
}
