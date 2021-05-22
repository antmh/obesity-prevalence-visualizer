<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\ {
    database\Repository,
    LineChart,
    BarChart,
    Table,
    StatisticsParameters,
    Visualization,
};

abstract class StatisticsController extends PresentationController
{
    abstract protected function getRepository(): Repository;

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
        $parameters = new StatisticsParameters($columns, $columnValues);
        if (!$parameters->isValid()) {
            return;
        }
        $visualization = Visualization::get($parameters, $this->getRepository());
        if ($parameters->getExport() !== null) {
            $visualization->export($parameters->getExport());
            return;
        }
        \views\View::render('statistics.php', [
            'accordion' => $accordion,
            'radioGroup' => $radioGroup,
            'checkboxGroup' => $checkboxGroup,
            'orderGroup' => $orderGroup,
            $parameters->getType() => $visualization,
        ]);
    }
}
