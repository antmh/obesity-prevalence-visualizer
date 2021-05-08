<?php

declare(strict_types=1);

namespace models\database;

abstract class Repository
{
    private const INSERT_CHUNK_SIZE = 500;

    public function __construct(protected \Sqlite3 $db, protected string $table, protected array $columnTypes)
    {
        if (!$this->tableExists()) {
            ini_set('memory_limit', '512M');
            set_time_limit(0);
            $this->create();
            $this->insertRows();
        }
    }

    public function getAllBy(array $selectedProperties = [], array $filterBy = [], array $orderBy = []): array | false
    {
        $selectStr = 'SELECT ';
        if ($selectedProperties === []) {
            $selectStr .= '* ';
        } else {
            foreach ($selectedProperties as $index => $selectedProperty) {
                if (!key_exists(lcfirst($selectedProperty), $this->columnTypes)) {
                    return false;
                }
                if ($index !== 0) {
                    $selectStr .= ', ';
                }
                $selectStr .= $selectedProperty;
            }
        }
        $selectStr .= ' FROM ' . $this->table;
        if ($filterBy !== []) {
            $selectStr .= ' WHERE ';
            foreach ($filterBy as $column => $value) {
                if ($column !== array_key_first($filterBy)) {
                    $selectStr .= ' AND ';
                }
                if (!key_exists(lcfirst($column), $this->columnTypes)) {
                    return false;
                }
                $selectStr .= $column . '=?';
            }
        }
        if ($orderBy !== []) {
            $selectStr .= ' ORDER BY ';
            foreach ($orderBy as $column => $order) {
                if ($column !== array_key_first($orderBy)) {
                    $selectStr .= ', ';
                }
                if (!key_exists(lcfirst($column), $this->columnTypes)) {
                    return false;
                }
                $selectStr .= $column . ' ' . $order;
            }
        }
        $selectStr .= ';';
        $stmt = $this->db->prepare($selectStr);
        $index = 1;
        foreach ($filterBy as $column => $value) {
            $stmt->bindValue($index, $value, $this->columnTypes[lcfirst($column)]);
            $index++;
        }
        $result = $stmt->execute();
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($rows, $row);
        }
        return $rows;
    }

    private function create(): void
    {
        $createStr = "CREATE TABLE $this->table (";
        $columns = array_keys($this->columnTypes);
        foreach ($columns as $index => $column) {
            if ($index !== 0) {
                $createStr .= ',';
            }
            $createStr .= $column . ' ';
            $createStr .= $this->getTypeName($this->columnTypes[$column]);
        }
        $createStr .= ');';
        $this->db->exec($createStr);
    }

    private function tableExists(): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*)
                                    FROM sqlite_master
                                    WHERE type=\'table\'
                                    AND name=:name');
        $stmt->bindValue(':name', $this->table, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_NUM)[0] === 1;
    }

    abstract protected function getRows(): array;

    private function insertRows()
    {
        $rows = $this->getRows();
        for ($chunk = 0; $chunk * self::INSERT_CHUNK_SIZE < count($rows); $chunk++) {
            $rowsToInsert = min(
                self::INSERT_CHUNK_SIZE,
                count($rows) - $chunk * self::INSERT_CHUNK_SIZE
            );
            $insertStr = $this->constructInsertStr($rowsToInsert);
            $stmt = $this->db->prepare($insertStr);
            $types = array_values($this->columnTypes);
            $position = 0;
            foreach (array_slice($rows, $chunk * self::INSERT_CHUNK_SIZE, $rowsToInsert) as $row) {
                if (count($row) !== count($types)) {
                    throw new DomainException('Invalid columns number');
                }
                foreach ($row as $column => $cell) {
                    $position++;
                    $stmt->bindValue($position, $cell, $types[$column]);
                }
            }
            $stmt->execute();
        }
    }

    private function constructInsertStr(int $rows): string
    {
        $insertStr = "INSERT INTO $this->table (";
        $columns = array_keys($this->columnTypes);
        foreach ($columns as $index => $column) {
            if ($index !== 0) {
                $insertStr .= ',';
            }
            $insertStr .= $column;
        }
        $insertStr .= ') VALUES';
        foreach (range(0, $rows - 1) as $row) {
            if ($row !== 0) {
                $insertStr .= ',';
            }
            $insertStr .= '(';
            foreach (range(0, count($columns) - 1) as $column) {
                if ($column !== 0) {
                    $insertStr .= ',';
                }
                $insertStr .= '?';
            }
            $insertStr .= ')';
        }
        $insertStr .= ';';
        return $insertStr;
    }

    private function getTypeName(int $type): string
    {
        return match ($type) {
            SQLITE3_INTEGER => 'INTEGER',
            SQLITE3_FLOAT   => 'REAL',
            SQLITE3_TEXT    => 'TEXT',
            SQLITE3_BLOB    => 'BLOB',
            default         => throw new DomainException('Invalid column type')
        };
    }

    public function getColumns(): array
    {
        return array_keys($this->columnTypes);
    }

    public function getColumnValues(): array
    {
        $columns = array_keys($this->columnTypes);
        $columnValues = [];
        foreach ($columns as $column) {
            $stmt = $this->db->prepare('SELECT DISTINCT ' . $column . ' FROM ' . $this->table);
            $result = $stmt->execute();
            $items = [];
            while ($item = $result->fetchArray(SQLITE3_NUM)) {
                array_push($items, $item[0]);
            }
            $columnValues[ucfirst($column)] = $items;
        }
        return $columnValues;
    }
}
