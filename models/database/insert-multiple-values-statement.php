<?php

declare(strict_types=1);

namespace models\database;

class InsertMultipleValuesStatement
{
    private \SQLite3Stmt $stmt;
    private int $position;

    public function __construct(\Sqlite3 $db, string $table, private int $rowsToAdd, private array $columns)
    {
        $str = "INSERT INTO $table (";
        for ($i = 0; $i < count($this->columns); $i++) {
            if ($i !== 0) {
                $str .= ',';
            }
            $str .= $this->columns[$i];
        }
        $str .= ') VALUES';
        for ($i = 0; $i < $this->rowsToAdd; $i++) {
            if ($i !== 0) {
                $str .= ',';
            }
            $str .= '(';
            for ($j = 0; $j < count($this->columns); $j++) {
                if ($j !== 0) {
                    $str .= ',';
                }
                $str .= '?';
            }
            $str .= ')';
        }
        $str .= ';';
        $this->stmt = $db->prepare($str);
        $this->position = 1;
    }

    public function addValue(mixed $value, int $type)
    {
        $this->stmt->bindValue($this->position, $value, $type);
        $this->position++;
        if ($this->done()) {
            $this->stmt->execute();
        }
    }

    public function done(): bool
    {
        return $this->position === count($this->columns) * $this->rowsToAdd + 1;
    }
}
