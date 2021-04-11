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
        $accordion = [
            [
                'name' => 'Year',
                'items' => [
                    2016,
                    2017,
                    2018,
                    2019,
                ],
            ],
            [
                'name' => 'Location',
                'items' => [
                    'Germany',
                    'Austria',
                    'Hungary',
                    'France',
                    'Spain',
                ],
            ],
            [
                'name' => 'Category',
                'items' => [
                    'Underweight',
                    'Normal weight',
                    'Overweight',
                    'Obese',
                ],
            ],
        ];
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
        \views\View::render('eurostat.php', [
            'accordion' => $accordion,
            'barChart' => new BarChart($values),
        ]);
    }
}
