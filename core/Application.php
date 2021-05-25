<?php

declare(strict_types=1);

namespace core;

use controllers\api\ProcessLoginController as controllersProcessLoginController;
use controllers\presentation\ {
    HomeController,
    EurostatController,
    ErrorController,
    LogoutController,
    WhoController,
    LoginController,
    ProcessLoginController,
    ProcessLogoutController,
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
            Router::post('/process-login', function () {
                (new ProcessLoginController())->post();
            });
            Router::get('/process-logout', function () {
                (new ProcessLogoutController())->get();
            });
            Router::get('/administration', function () {
                (new AdministrationController())->index();
            });
            Router::get('/administration/who', function () {
                (new AdministrationWhoController())->index();
            });
            Router::get('/administration/eurostat', function () {
                (new AdministrationEurostatController())->index();
            });
            Router::post('/api/eurostat', function () {
                (new api\EurostatController())->post();
            });
            Router::post('/api/who', function () {
                (new api\WhoController())->post();
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
