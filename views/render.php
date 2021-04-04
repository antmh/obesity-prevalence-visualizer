<?php

declare(strict_types=1);

namespace views;

function render(string $page)
{
    require 'views/components/header.php';
    require 'views/pages/' . $page . '.php';
    require 'views/components/footer.php';
}
