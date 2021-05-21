<?php

declare(strict_types=1);

namespace controllers\ajax;

use controllers\ajax\DataController;
use models\database\ {
    Database,
    EurostatRepository,
};

class EurostatDataController extends DataController
{
    protected function getRepository(): EurostatRepository
    {
        return Database::getInstance()->getEurostatRepository();
    }
}
