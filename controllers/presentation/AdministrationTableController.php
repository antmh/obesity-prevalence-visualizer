<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\ {
    database\Repository,
    Table
};

abstract class AdministrationTableController
{
    abstract protected function getRepository(): Repository;

    abstract public function getParam(): string;

    public function index(): void
    {
        $repository = $this->getRepository();
        $columns = $repository->getColumns();
        \views\View::render('administrationTable.php', [
            'columns' => $columns,
            'param' => $this->getParam(),
        ]);
    }
}
