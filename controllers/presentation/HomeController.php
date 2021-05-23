<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\Controller;
use models\ {
    database\Database,
    Table,
    BarChart,
    LineChart,
};

class HomeController
{
    public function index(): void
    {
        $eurostatRepository = Database::getInstance()->getEurostatRepository();
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
        $lineChartValues = $eurostatRepository->getAllBy(
            filterBy: [
                'location' => 'Austria',
            ],
        );
        \views\View::render('home.php', [
            'table' => new Table($values),
            'barChart' => new BarChart($values, true, true),
            'lineChart' => new LineChart($lineChartValues, true, true),
        ]);
    }
}
