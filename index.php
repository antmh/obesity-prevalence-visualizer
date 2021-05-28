<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';
require_once 'core/Autoload.php';

use core\ {
    Autoload,
    Application,
    Environment,
};

// Automatically load classes
Autoload::register();

// Load environment variables from .env
Environment::load();

(new Application())->run();
