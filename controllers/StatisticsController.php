<?php

declare(strict_types=1);

namespace controllers;

use models\ {
    database\Repository,
    LineChart,
    BarChart,
    Table,
};

abstract class StatisticsController extends Controller
{
    abstract protected function getRepository(): Repository;

    abstract protected function getView(): string;

    public function index(): void
    {
        $repository = $this->getRepository();
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();
        $accordion = [];
        foreach ($columnValues as $key => $val) {
            array_push($accordion, [
                'name' => $key,
                'items' => $val,
            ]);
        }
        $radioGroup = [
            'name' => 'Type',
            'default' => true,
            'items' => [
                'Bar chart',
                'Line chart',
                'Table',
            ]
        ];
        $checkboxGroup = [
            'name' => 'Selected properties',
            'items' => $columns
        ];
        $orderGroup = [
            'name' => 'Order',
            'items' => $columns
        ];
        $parameters = $this->getParameters($columns, $columnValues);
        if ($parameters['type'] === 'barChart') {
            if (in_array('Value', $parameters['selectedProperties'])) {
                $values = $repository->getAllBy(
                    $parameters['selectedProperties'],
                    $parameters['filterBy'],
                    $parameters['orderBy']
                );
                $visualization = new BarChart($values, true);
            } else {
                array_push($parameters['selectedProperties'], 'Value');
                $values = $repository->getAllBy(
                    $parameters['selectedProperties'],
                    $parameters['filterBy'],
                    $parameters['orderBy']
                );
                $visualization = new BarChart($values, false);
            }
        } elseif ($parameters['type'] === 'lineChart') {
            $showValues = true;
            if (!in_array('Value', $parameters['selectedProperties'])) {
                $showValues = false;
                array_push($parameters['selectedProperties'], 'Value');
            }
            $showYears = true;
            if (!in_array('Year', $parameters['selectedProperties'])) {
                $showYears = false;
                array_push($parameters['selectedProperties'], 'Year');
            }
            $values = $repository->getAllBy(
                $parameters['selectedProperties'],
                $parameters['filterBy'],
                $parameters['orderBy']
            );
            $visualization = new LineChart($values, $showValues, $showYears);
        } else {
            $values = $repository->getAllBy(
                $parameters['selectedProperties'],
                $parameters['filterBy'],
                $parameters['orderBy']
            );
            $visualization = new Table($values);
        }
        \views\View::render('eurostat.php', [
            'accordion' => $accordion,
            'radioGroup' => $radioGroup,
            'checkboxGroup' => $checkboxGroup,
            'orderGroup' => $orderGroup,
            $parameters['type'] => $visualization,
        ]);
    }

    private function getParameters(array $columns, array $columnValues): ?array
    {
        $type = null;
        $selectedProperties = [];
        $orderBy = [];
        $filterBy = [];
        foreach ($_GET as $key => $val) {
            $key = str_replace('_', ' ', $key);
            if ($key === 'Type') {
                $type = match ($_GET['Type']) {
                    'Bar chart' => 'barChart',
                    'Line chart' => 'lineChart',
                    'Table' => 'table',
                    default => null,
                };
            } elseif ($key === 'checked') {
                foreach ($val as $checkedProperty => $checked) {
                    if (!in_array($checkedProperty, $columns) || $checked !== 'on') {
                        return null;
                    }
                    array_push($selectedProperties, $checkedProperty);
                }
            } elseif ($key === 'order') {
                foreach ($val as $orderedProperty) {
                    if (!key_exists('name', $orderedProperty)) {
                        return null;
                    }
                    if (!in_array($orderedProperty['name'], $columns)) {
                        return null;
                    }
                    if (key_exists('descending', $orderedProperty) && $orderedProperty['descending'] === 'on') {
                        $orderBy[$orderedProperty['name']] = 'desc';
                        continue;
                    } elseif (!key_exists('descending', $orderedProperty)) {
                        $orderBy[$orderedProperty['name']] = 'asc';
                        continue;
                    }
                    return null;
                }
            } elseif (in_array($key, $columns)) {
                if (!in_array($val, $columnValues[$key])) {
                    return null;
                }
                $filterBy[$key] = $val;
            } else {
                return null;
            }
        }
        if ($type === null) {
            $type = 'barChart';
        }
        if ($type === 'barChart') {
            array_push($selectedProperties, 'value');
        }
        return [
            'type' => $type,
            'selectedProperties' => $selectedProperties,
            'orderBy' => $orderBy,
            'filterBy' => $filterBy,
        ];
    }
}
