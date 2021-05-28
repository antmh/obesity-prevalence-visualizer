<?php

namespace views;

use models\Authentication;

abstract class View
{
    public static function render(string $body, array $args = [], string $header = null, string $footer = null): void
    {
        $loggedIn = Authentication::validate();
        include('views/components/header.php');

        extract($args, EXTR_SKIP);
        include('views/pages/' . $body);

        include('views/components/footer.php');
    }
}
