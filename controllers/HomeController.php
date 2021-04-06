<?php

declare(strict_types=1);

namespace controllers;

class HomeController extends Controller
{
    public function index(): void
    {
        $eurostatRepository = \models\database\Database::getInstance()->getEurostatRepository();
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
        \views\View::render('home.php', ['values' => $values]);
    }
}
