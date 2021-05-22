<?php

declare(strict_types=1);

namespace core;

class Router
{
    public static function get(string $path, callable $arg): void
    {
        self::executeIfMatches('GET', $path, $arg);
    }

    public static function post(string $path, callable $arg): void
    {
        self::executeIfMatches('POST', $path, $arg);
    }

    public static function put(string $path, callable $arg): void
    {
        self::executeIfMatches('PUT', $path, $arg);
    }

    public static function delete(string $path, callable $arg): void
    {
        self::executeIfMatches('DELETE', $path, $arg);
    }

    private static function executeIfMatches(string $method, string $path, $arg): void
    {
        if ($_SERVER['REQUEST_METHOD'] === $method && self::pathMatches($path)) {
            $arg();
        }
    }

    private static function pathMatches(string $path): bool
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($path === '') {
            $path = '/';
        }
        if (strlen($url) === 0 || $url[0] !== '/') {
            $url = '/' . $url;
        }
        if ($url[strlen($url) - 1] !== '/') {
            $url .= '/';
        }
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }
        if ($path[strlen($path) - 1] !== '/') {
            $path .= '/';
        }
        return $url === $path;
    }
}
