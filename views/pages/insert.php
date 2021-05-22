<?php

use models\database\Database;

if(isset($_GET['table']))
{
    if(($_GET['table']) === 'eurostat')
    {
        $repository = Database::getInstance()->getEurostatRepository();
        header('location:administrationEurostat');
    }
    elseif(($_GET['table']) === 'who')
    {
        $repository = Database::getInstance()->getWhoRepository();
        header('location:administrationWho');
    }
    $values = [];
    foreach ($repository->getColumns() as $column)
    {
        array_push($values,$_GET[str_replace(' ','_',$column)]);
    }
    $repository->insertDataRow($values);
}
elseif(isset($_GET['data']))
{
    $data = $_GET['data'];
    if($data === 'eurostat') {
        $eurostatRepository = Database::getInstance()->getEurostatRepository();
        $eurostatRepository->insertDataRows();
        header('location:administrationEurostat');
    }
    if($data === 'who') {
        $whoRepository = Database::getInstance()->getWhoRepository();
        $whoRepository->insertDataRows();
        header('location:administrationWho');
    }
}