<?php

declare(strict_types=1);

require 'core/Autoload.php';

use core\ {
    Autoload,
    Application,
};

Autoload::register();

(new Application())->run();
