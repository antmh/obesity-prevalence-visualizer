<?php

declare(strict_types=1);

namespace controllers\presentation;

abstract class PresentationController
{
    abstract public function index(): void;
}
