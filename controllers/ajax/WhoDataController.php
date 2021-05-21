<?php

declare(strict_types=1);

namespace controllers\ajax;

use controllers\ajax\DataController;
use models\database\ {
    Database,
    WhoRepository,
};

class WhoDataController extends DataController
{
    protected function getRepository(): WhoRepository
    {
        return Database::getInstance()->getWhoRepository();
    }
}
