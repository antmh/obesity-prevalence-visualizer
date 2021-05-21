<?php

declare(strict_types=1);

namespace controllers\presentation;

use controllers\Controller;

class LoginController extends Controller
{
    public function index(): void
    {
        if (isset($_POST['verifyLogin'])) {
            \models\Login::verifyLogin($_POST['email'], $_POST['password']);
        }
        \views\View::render('login.php');
    }
}
