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
            Router::add('GET', '/', function () {
                (new HomeController())->index();
            });
            Router::add('GET', '/eurostat', function () {
                (new EurostatController())->index();
            });
            Router::add('GET', '/who', function () {
                (new WhoController())->index();
            });
            Router::add('GET', '/login', function () {
                (new LoginController())->index();
            });
            Router::add('POST', '/process-login', function () {
                (new ProcessLoginController())->post();
            });
            Router::add('GET', '/process-logout', function () {
                (new ProcessLogoutController())->get();
            });
            Router::add('GET', '/administration', function () {
                (new AdministrationController())->index();
            });
            Router::add('GET', '/administration/who', function () {
                (new AdministrationWhoController())->index();
            });
            Router::add('GET', '/administration/eurostat', function () {
                (new AdministrationEurostatController())->index();
            });
            Router::add('POST', '/api/eurostat', function () {
                (new api\EurostatController())->post();
            });
            Router::add('POST', '/api/who', function () {
                (new api\WhoController())->post();
            });
            Router::add('GET', '/api/eurostat', function () {
                (new api\EurostatController())->get();
            });
            Router::add('GET', '/api/who', function () {
                (new api\WhoController())->get();
            });
            Router::add('DELETE', '/api/who', function () {
                (new api\WhoController())->delete();
            });
            Router::add('DELETE', '/api/eurostat', function () {
                (new api\EurostatController())->delete();
            });
            Router::add('POST', '/api/login', function () {
                (new api\LoginController())->post();
            });
            Router::addNumbered('DELETE', '/api/eurostat', function ($number) {
                (new api\EurostatController())->deleteRow($number);
            });
            Router::addNumbered('DELETE', '/api/who', function ($number) {
                (new api\WhoController())->deleteRow($number);
            });
            Router::add('POST', '/api/account/valid', function () {
                (new api\AccountController())->isPasswordValid();
            });
            Router::add('PUT', '/api/account/username', function () {
                (new api\AccountController())->changeUsername();
            });
            Router::add('PUT', '/api/account/password', function () {
                (new api\AccountController())->changePassword();
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
