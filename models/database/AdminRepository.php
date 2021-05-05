<?php

declare(strict_types=1);

namespace models\database;

class AdminRepository extends Repository
{
    public function __construct(\Sqlite3 $db)
    {
        parent::__construct(
            db: $db,
            table: 'admin',
            columnTypes: [
                'email' => SQLITE3_TEXT,
                'password' => SQLITE3_TEXT
            ]
        );
    }
    protected function getRows(): array
    {
        $rows = [];
            $columns = [
                "root@opvmail.com",
                "root"
            ];
            array_push($rows, $columns);
            return $rows;
    }
}
