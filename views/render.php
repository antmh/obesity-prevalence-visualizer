<?php

declare(strict_types=1);

namespace views;

function render(string $page)
{
    include 'views/components/header.php';
    include 'views/pages/' . $page . '.php';
    include 'views/components/footer.php';
}
