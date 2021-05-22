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
    AdministrationWhoController,
    AdministrationEurostatController,
};
use controllers\api;

class Application
{
    public function run(): void
    {
        try {
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
                (new LogoutController())->index();
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
        } catch (ApiException $e) {
            http_response_code($e->getStatusCode());
            echo json_encode(['message' => $e->getMessage()]);
        } catch (PresentationException $e) {
            http_response_code($e->getStatusCode());
            (new ErrorController())->index($e->getMessage());
        }
    }
}
