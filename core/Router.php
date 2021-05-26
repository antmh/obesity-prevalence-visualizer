<?php

declare(strict_types=1);

namespace core;

class Router
{
    public static function add(string $method, string $path, callable $arg): bool
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = self::normalize($path);
        $url = self::normalize($url);
        if ($_SERVER['REQUEST_METHOD'] === $method && $url === $path) {
            $arg();
            return true;
        }
        return false;
    }

    public static function addNumbered(string $method, string $path, callable $arg): bool
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = self::normalize($path);
        $url = self::normalize($url);
        if (preg_match('/(.*\\/)(\\d+)\\/$/', $url, $matches) === 1 && $matches[1] === $path) {
            $arg(intval($matches[2]));
            return true;
        } else {
            return false;
        }
    }

    private static function normalize(string $path): string
    {
        if ($path === '') {
            $path = '/';
        }
        if ($path[strlen($path) - 1] !== '/') {
            $path .= '/';
        }
        return $path;
    }
}
