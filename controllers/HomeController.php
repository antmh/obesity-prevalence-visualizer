<?php

declare(strict_types=1);

namespace controllers;

use models\ {
    database\Database,
    Table,
};

class HomeController extends Controller
{
    public function index(): void
    {
        $eurostatRepository = Database::getInstance()->getEurostatRepository();
        $values = $eurostatRepository->getAllBy(
            selectedProperties: [
                'location',
                'value',
            ],
            filterBy: [
                'category' => 'Obese',
                'year' => '2017',
            ],
            orderBy: [
                'location' => 'asc',
            ],
        );
        \views\View::render('home.php', ['table' => new Table($values)]);
    }
}
