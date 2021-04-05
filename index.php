<?php

declare(strict_types=1);

function autoload($class)
{
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        $class = str_replace("\\", "/", $class);
    }
    if (file_exists($class . '.php')) {
        include($class . '.php');
    } elseif (Config::SHOW_ERRORS) {
        die('Couldn\'t load ' . $class . '.');
    }
}

spl_autoload_register('autoload');

$application = new Application();
$application->run();
