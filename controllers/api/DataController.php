<?php

declare(strict_types=1);

namespace controllers\api;

use models\ {
    database\Repository,
    StatisticsParameters,
    Visualization,
};
use core\ApiException;

abstract class DataController
{
    abstract protected function getRepository(): Repository;

    public function get(): void
    {
        header('Content-Type: application/json');
        $repository = $this->getRepository();
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();
        $parameters = new StatisticsParameters($columns, $columnValues);
        if (!$parameters->isValid()) {
            $this->invalidParameters();
            return;
        }
        header('X-PageCount: ' . $repository->getPageCount());
        $visualization = Visualization::get($parameters, $this->getRepository());
        if ($parameters->getExport() === null) {
            echo json_encode($visualization);
        } else {
            $visualization->export($parameters->getExport());
        }
    }

    public function post(): void
    {
        header('Content-Type: application/json');
        $repository = $this->getRepository();
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();
        $data = json_decode(file_get_contents('php://input'), associative: true);
        if ($data === null) {
            $this->invalidParameters();
            return;
        }
        foreach ($data as $key => $value) {
            if (!in_array($key, $columns)) {
                throw new ApiException('Invalid column ' . $key, 200);
            }
        }
        $values = [];
        foreach ($columns as $column) {
            array_push($values, $data[$column]);
        }
        $repository->insertDataRow($values);
        echo json_encode(["message" => "Inserted row successfully"]);
    }

    public function invalidParameters(): void
    {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid parameters']);
    }
}
