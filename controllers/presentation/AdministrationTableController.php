<?php

declare(strict_types=1);

namespace controllers\presentation;

use models\ {
    database\Repository,
    Table
};

abstract class AdministrationTableController extends PresentationController
{
    abstract protected function getRepository(): Repository;

    abstract public function getParam(): string;

    public function index(): void
    {
        $repository = $this->getRepository();
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();

        $dataManagementGroup = [
            'name' => 'Data management',
            'items' => $columns
        ];
        $values = $repository->getDataPage(1);
        $visualization = new Table($values,deletable:true);
        \views\View::render('administration/administrationTable.php', [
            'dataManagementGroup' => $dataManagementGroup,
            'table' => $visualization,
            'param' => $this->getParam(),
        ]);
    }
}