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

    public function getCredentials(): array
    {
        return $this->sqlite->query('SELECT username, password
                                     FROM admin')->fetchArray(SQLITE3_ASSOC);
    }

    public function adminCredentialsValid(string $username, string $password): bool
    {
        $result = $this->getCredentials();
        return $result['username'] === $username && $result['password'] === $password;
    }

    public function changeUsername(string $username): void
    {
        $stmt = $this->sqlite->prepare('UPDATE admin SET username = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();
    }

    public function changePassword(string $password): void
    {
        $stmt = $this->sqlite->prepare('UPDATE admin SET password = :password');
        $stmt->bindValue(':password', $password);
        $stmt->execute();
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
