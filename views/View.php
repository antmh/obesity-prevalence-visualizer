<?php

namespace views;

abstract class View
{
    private const DEFAULT_HEADER = 'header.php';
    private const DEFAULT_FOOTER = 'footer.php';

    public static function render($body, array $args = [], $header = null, $footer = null)
    {
        if ($header === null) {
            include('views/components/' . self::DEFAULT_HEADER);
        } else {
            include('views/components/' . $header);
        }

        extract($args, EXTR_SKIP);
        include('views/pages/' . $body);

        if ($footer === null) {
            include('views/components/' . self::DEFAULT_FOOTER);
        } else {
            include('views/components/' . $footer);
        }
    }
}