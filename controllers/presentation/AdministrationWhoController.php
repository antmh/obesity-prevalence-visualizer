<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\database\ {
    Database,
    WhoRepository,
};

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
