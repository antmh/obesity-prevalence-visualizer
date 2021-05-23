<?php

declare(strict_types=1);

namespace models;

use DateTimeImmutable;

class Token
{
    public function __construct(private string $content, private \DateTimeImmutable $expiration)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getExpiration(): \DateTimeImmutable
    {
        return $this->expiration;
    }
}
