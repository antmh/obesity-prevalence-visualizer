<?php

declare(strict_types=1);

namespace controllers;

use models\ {
    database\Database,
    BarChart,
};

class EurostatController extends Controller
{
    public function index(): void
    {
        $eurostatRepository = Database::getInstance()->getEurostatRepository();
        $columnValues = $eurostatRepository->getColumnValues();
        $columns = array_map(function ($elem) {
            return $elem['name'];
        }, $columnValues);
        $accordion = $columnValues;
        $radioGroup = [
            'name' => 'Type',
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
        $values = $eurostatRepository->getAllBy(
            selectedProperties: [
                'location',
                'value',
                'year',
            ],
            filterBy: [
                'category' => 'Obese',
            ],
            orderBy: [
                'location' => 'asc',
            ],
        );
        \views\View::render('eurostat.php', [
            'accordion' => $accordion,
            'barChart' => new BarChart($values),
            'radioGroup' => $radioGroup,
            'checkboxGroup' => $checkboxGroup,
            'orderGroup' => $orderGroup,
        ]);
    }
}
