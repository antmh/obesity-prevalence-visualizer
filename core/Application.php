<?php

declare(strict_types=1);

namespace core;

use controllers\ClearPresentationController;
use controllers\presentation\ {
    HomePresentationController,
    EurostatController,
    ErrorPresentationController,
    LogoutPresentationController,
    WhoController,
    LoginPresentationController,
    AdministrationPresentationController,
    AdministrationWhoController,
    AdministrationEurostatController,
    AdminController,
};
use controllers\api;

class Application
{
    public function run(): void
    {
        Router::get('/', function () {
            (new HomePresentationController())->index();
        });
        Router::get('/eurostat', function () {
            (new EurostatController())->index();
        });
        Router::get('/who', function () {
            (new WhoController())->index();
        });
        Router::get('/login', function () {
            (new LoginPresentationController())->index();
        });
        Router::get('/administration', function () {
            (new AdministrationPresentationController())->index();
        });
        Router::get('/administrationWho', function () {
            (new AdministrationWhoController())->index();
        });
        Router::get('/administrationEurostat', function () {
            (new AdministrationEurostatController())->index();
        });
        Router::get('/clear', function () {
            (new api\ClearPresentationController())->index();
        });
        Router::get('/delete', function () {
            (new api\DeletePresentationController())->index();
        });
        Router::get('/insert', function () {
            (new api\InsertPresentationController())->index();
        });
        Router::get('/logout', function () {
            (new LogoutPresentationController())->index();
        });
        Router::get('/api/eurostat', function () {
            (new api\EurostatController())->get();
        });
        Router::get('/api/who', function () {
            (new api\WhoController())->get();
        });
        Router::post('/api/login', function () {
            (new api\LoginController())->post();
        });
        if (!Router::executed()) {
            (new ErrorPresentationController())->index();
        }
    }
}
