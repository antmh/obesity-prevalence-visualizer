<?php

declare(strict_types=1);

namespace models;

class Table
{
    private array $header;
    private array $body;

    public function __construct(array $values)
    {
        $this->header = [];
        $this->body = [];
        foreach (array_keys($values[0]) as $headerCell) {
            array_push($this->header, ucfirst($headerCell));
        }
        foreach ($values as $row) {
            array_push($this->body, array_values($row));
        }
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}
