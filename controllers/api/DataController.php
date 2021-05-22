<?php

declare(strict_types=1);

namespace controllers\api;

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
        if (!$parameters->isValid()) {
            $this->invalidParameters();
            return;
        }
        $visualization = Visualization::get($parameters, $this->getRepository());
        if ($parameters->getExport() === null) {
            header('Content-Type: application/json');
            echo json_encode($visualization);
        }
    }

    public function invalidParameters(): void
    {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid parameters']);
    }
}
