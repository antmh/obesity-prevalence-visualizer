<?php

declare(strict_types=1);

namespace models;

class Table implements \JsonSerializable
{
    private array $header = [];
    private array $body = [];
    private ?array $deleteUrls = null;

    public function __construct(array $values = [], ?string $table = null)
    {
        if($values !== []) {
            foreach (array_keys($values[0]) as $headerCell) {
                if ($headerCell !== 'rowid') {
                    array_push($this->header, ucfirst($headerCell));
                }
            }
            foreach ($values as $row) {
                $bodyRow = [];
                foreach ($row as $column => $cell) {
                    if ($column !== 'rowid') {
                        array_push($bodyRow, $cell);
                    }
                }
                array_push($this->body, $bodyRow);
            }
            if (in_array('rowid', array_keys($values[0]))) {
                $this->deleteUrls = [];
                foreach ($values as $row) {
                    array_push($this->deleteUrls, '/api/' . $table . '/' . $row['rowid']);
                }
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
        if ($this->deleteUrls !== null) {
            return [
                'header' => $this->header,
                'body' => $this->body,
                'deleteUrls' => $this->deleteUrls,
            ];
        } else {
            return [
                'header' => $this->header,
                'body' => $this->body,
            ];
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
