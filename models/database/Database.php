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
    private AdminRepository $adminRepository;
    private function __construct()
    {
        $this->sqlite = new \Sqlite3('database.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->whoRepository = new WhoRepository($this->sqlite);
        $this->eurostatRepository = new EurostatRepository($this->sqlite);
        if (!$this->adminTableExists()) {
            $this->createAdminTable();
        }
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

    public function adminCredentialsValid(string $username, string $password): bool
    {
        $result = $this->sqlite->query('SELECT username, password
                                        FROM admin')->fetchArray(SQLITE3_ASSOC);
        return $result['username'] === $username && $result['password'] === $password;
    }

    private function adminTableExists(): bool
    {
        $result = $this->sqlite->query('SELECT COUNT(*)
                                        FROM sqlite_master
                                        WHERE type=\'table\'
                                        AND name=\'admin\'');
        return $result->fetchArray(SQLITE3_NUM)[0] === 1;
    }

    private function createAdminTable(): void
    {
        $this->sqlite->exec('CREATE TABLE admin (username TEXT, password TEXT);');
        $this->sqlite->exec('INSERT INTO admin (username, password)
                             VALUES (\'admin\', \'admin\')');
    }
}
