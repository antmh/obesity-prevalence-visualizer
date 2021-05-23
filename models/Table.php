<?php

declare(strict_types=1);

namespace models;

class Table implements \JsonSerializable
{
    private array $header=[];
    private array $body=[];
    private int $indexRow=0;

    public function __construct(array $values = [], private bool $deletable = false)
    {
        if($values != []) {
            foreach (array_keys($values[0]) as $headerCell) {
                array_push($this->header, ucfirst($headerCell));
            }
            foreach ($values as $row) {
                array_push($this->body, array_values($row));
            }
        }
    }

    public function export(string $type): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        $file = fopen('php://output', 'w');
        foreach ($this->body as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'header' => $this->header,
            'body' => $this->body,
        ];
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function isDeletable(): bool
    {
        return $this->deletable;
    }

    public function getDeleteString(): string
    {
        return "delete". $this->indexRow;
    }
}
