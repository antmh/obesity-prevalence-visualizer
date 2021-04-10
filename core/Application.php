<?php

declare(strict_types=1);

namespace core;

use controllers\{HomeController, EurostatController, ErrorController, WHOController, LoginController, AdministrationController};

class Application
{
    public function run(): void
    {
        $homeController = new HomeController();
        $errorController = new ErrorController();
        $eurostatController = new EurostatController();
        $whoController = new WHOController();
        $loginController = new LoginController();
        $administrationController = new AdministrationController();
        
        Router::get('/', function () use ($homeController) {
            $homeController->index();
        });
        Router::get('/eurostat', function () use ($eurostatController) {
            $eurostatController->index();
        });
        Router::get('/who', function () use ($whoController) {
            $whoController->index();
        });
        Router::get('/login', function () use ($loginController) {
            $loginController->index();
        });
        Router::get('/administration', function () use ($administrationController) {
            $administrationController->index();
        });
        if (!Router::executed()) {
            $errorController->index();
        }
    }
}
