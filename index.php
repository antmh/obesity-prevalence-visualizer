<?php

//include 'core/Application.php';
//include 'controllers/SiteController.php';
include './vendor/autoload.php';
use controllers\SiteController;
$app = new core\Application(dirname(__DIR__) . "\obesity-prevalence-visualizer");


$app->router->get('/', function() {
	return 'hello world';
});


//$app->router->get('/services', [SiteController::class, 'services']);
$app->router->get('/services', 'services');
$app->router->get('/home', 'home');
$app->router->get('/about', 'about');
$app->run();