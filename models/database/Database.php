<?php

declare(strict_types=1);

namespace models\database;

class Database
{
    private static Database $database;
    private \Sqlite3 $sqlite;

    private const CHUNK_SIZE = 500;
    private WhoRepository $whoRepository;
    private EurostatRepository $eurostatRepository;

    private function __construct()
    {
        $this->sqlite = new \Sqlite3('database.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->whoRepository = new WhoRepository($this->sqlite);
        $this->eurostatRepository = new EurostatRepository($this->sqlite);
    }

    public static function getInstance(): Database
    {
        if (!isset(self::$database)) {
            self::$database = new Database();
        }
        return self::$database;
    }

    public function getEurostatRepository(): EurostatRepository
    {
        return $this->eurostatRepository;
    }

    public function getWhoRepository(): WhoRepository
    {
        return $this->whoRepository;
    }
}
