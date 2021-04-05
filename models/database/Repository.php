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
}
