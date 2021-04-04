<?php

declare(strict_types=1);

require 'controllers/home.php';
require 'views/render.php';
require 'models/database/database.php';
require 'models/database/repository.php';
require 'models/database/who-repository.php';
require 'models/database/eurostat-repository.php';

$db = new models\database\Database();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($url === '/') {
    controllers\home\index();
} else {
    echo 'page not found';
}
