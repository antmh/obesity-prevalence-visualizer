<?php
use models\database\Database;
$row=0;
if(isset($_GET['eurostat']))
{
    $repository = Database::getInstance()->getEurostatRepository();
    header('location:administrationEurostat');
}
elseif(isset($_GET['who']))
{
    $repository = Database::getInstance()->getWhoRepository();
    header('location:administrationWho');
}
$repository->deleteRow($_GET['row']);