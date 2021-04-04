<?php

namespace views;

abstract class View {
    const DEFAULT_HEADER = 'header.php';
    const DEFAULT_FOOTER = 'footer.php';

    public static function render($body, $header = null, $footer = null)
    {
        if ($header == null)
            include('views/components/'.self::DEFAULT_HEADER);
        else
            include('views/components/'.$header);

        include('views/pages/'.$body);

        if ($footer == null)
            include('views/components/'.self::DEFAULT_FOOTER);
        else
            include('views/components/'.$footer);
    }
}