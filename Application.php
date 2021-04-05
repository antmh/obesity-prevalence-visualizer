<?php

use controllers\
{
    AboutController,
    ServicesController,
    HomeController,
    EurostatController,
    ErrorController
};

class Application {
    public function run()
    {
        $homeController = new HomeController();
        $servicesController = new ServicesController();
        $aboutController = new AboutController();
        $errorController = new ErrorController();
        $eurostatController = new EurostatController();

        Router::get('/home', function () use ($homeController) {
            $homeController->index();
        });
        Router::get('/eurostat', function () use ($eurostatController) {
            $eurostatController->index();
        });
        Router::get('/services', function () use ($servicesController) {
            $servicesController->index();
        });
        Router::get('/about', function () use ($aboutController) {
            $aboutController->index();
        });
        Router::get('/', function () use ($homeController) {
            $homeController->index();
        });
        if (!Router::executed()) $errorController->index();
    }
}
