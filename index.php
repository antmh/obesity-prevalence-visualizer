<?php

declare(strict_types=1);

include 'controllers/home.php';
include 'views/render.php';
include 'models/database.php';

$db = new models\Database();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($url === '/') {
    controllers\home\index();
} else {
    echo 'page not found';
}
