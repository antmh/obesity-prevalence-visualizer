<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\{AdministrationWho, database\Database, database\WhoRepository, Table};

class AdministrationWhoController extends AdministrationTableController
{
    protected function getRepository(): WhoRepository
    {
        return Database::getInstance()->getWhoRepository();
    }
    public function getParam(): string
    {
        return 'who';
    }
}
