<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\{AdministrationEurostat, database\Database, database\EurostatRepository, Table};

class AdministrationEurostatController extends AdministrationTableController
{
    protected function getRepository(): EurostatRepository
    {
        return Database::getInstance()->getEurostatRepository();
    }

    public function getParam(): string
    {
        return 'eurostat';
    }
}
