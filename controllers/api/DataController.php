<?php

declare(strict_types=1);

namespace controllers\api;

use models\ {
    database\Repository,
    StatisticsParameters,
    Visualization,
    Authentication,
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
            throw new ApiException('Invalid parameters', 400);
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
        $this->validate();
        header('Content-Type: application/json');
        $repository = $this->getRepository();
        $data = file_get_contents('php://input');
        if ($data === '' && $_GET['all'] === '') {
            $repository->insertDataRows();
            echo json_encode(['message' => 'Inserted rows successfully']);
            return;
        }
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();
        $json = json_decode($data, associative: true);
        if ($json === null) {
            throw new ApiException('Invalid parameters', 400);
        }
        foreach ($json as $key => $value) {
            if (!in_array($key, $columns)) {
                throw new ApiException('Invalid column ' . $key, 200);
            }
        }
        $values = [];
        foreach ($columns as $column) {
            array_push($values, $json[$column]);
        }
        $repository->insertDataRow($values);
        echo json_encode(["message" => "Inserted row successfully"]);
    }

    public function delete(): void
    {
        $this->validate();
        header('Content-Type: application/json');
        $repository = $this->getRepository();
        $repository->clearData();
        echo json_encode(['message' => 'Deleted all rows']);
    }

    public function deleteRow(int $number): void
    {
        $this->validate();
        header('Content-Type: application/json');
        $data = file_get_contents('php://input');
        $repository = $this->getRepository();
        $repository->deleteRow($number);
        echo json_encode(['message' => 'Deleted row']);
    }

    private function validate()
    {
        if (!Authentication::validate()) {
            throw new ApiException('Authentication required', 401);
        }
    }
}
