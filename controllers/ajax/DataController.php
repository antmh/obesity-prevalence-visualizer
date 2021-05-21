<?php

declare(strict_types=1);

namespace controllers\ajax;

use models\ {
    database\Repository,
    StatisticsParameters,
    Visualization,
};

abstract class DataController
{
    abstract protected function getRepository(): Repository;

    public function get(): void
    {
        $repository = $this->getRepository();
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();
        $parameters = new StatisticsParameters($columns, $columnValues);
        $visualization = Visualization::get($parameters, $this->getRepository());
        if ($parameters->getExport() === null) {
            header('Content-Type: application/json');
            echo json_encode($visualization);
        }
    }
}
