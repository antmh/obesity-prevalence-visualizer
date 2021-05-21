<?php

declare(strict_types=1);

namespace core;

use controllers\presentation\ {
    HomeController,
    EurostatController,
    ErrorController,
    LogoutController,
    WhoController,
    LoginController,
    AdministrationController,
    AdminController,
};
use controllers\ajax\ {
    EurostatDataController,
    WhoDataController,
};

class Application
{
    public function run(): void
    {
        Router::get('/', function () {
            (new HomeController())->index();
        });
        Router::get('/eurostat', function () {
            (new EurostatController())->index();
        });
        Router::get('/who', function () {
            (new WhoController())->index();
        });
        Router::get('/login', function () {
            (new LoginController())->index();
        });
        Router::get('/administration', function () {
            (new AdministrationController())->index();
        });
        Router::get('/logout', function () {
            (new LogoutController())->index();
        });
        Router::get('/api/eurostat', function () {
            (new EurostatDataController())->get();
        });
        Router::get('/api/who', function () {
            (new WhoDataController())->get();
        });
        if (!Router::executed()) {
            (new ErrorController())->index();
        }
    }
}
