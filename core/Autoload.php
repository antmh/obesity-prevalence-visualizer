<?php

declare(strict_types=1);

namespace core;

class Autoload
{
    public static function register(): void
    {
        spl_autoload_register(['core\\Autoload', 'autoload']);
    }

    private static function autoload($class): void
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
}
