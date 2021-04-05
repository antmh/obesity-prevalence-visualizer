<?php

declare(strict_types=1);

namespace models\database;

class Database
{
    private \Sqlite3 $sqlite;

    private const CHUNK_SIZE = 500;
    private Repository $whoRepository;
    private Repository $eurostatRepository;

    public function __construct()
    {
        $this->sqlite = new \Sqlite3('database.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->whoRepository = new WhoRepository($this->sqlite);
        $this->eurostatRepository = new EurostatRepository($this->sqlite);
    }
}
