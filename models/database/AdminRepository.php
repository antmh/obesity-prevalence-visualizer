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
        if($this->isClear())
        {
            $this->insertDataRow(array("root@opv.com","root"));
        }
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

    public function getDataPage(int $index): array | false {}

    public function updatePassword(string $password): void
    {
        $updateStr = "UPDATE " . $this->table . " SET password='" . $password . "';";
        $stmt = $this->db->prepare($updateStr);
        $stmt->execute();
    }

    public function updateEmailAdress(string $email): void
    {
        $updateStr = "UPDATE " . $this->table . " SET email='" . $email . "';";
        $stmt = $this->db->prepare($updateStr);
        $stmt->execute();
    }

    public function getEmailAdress(): string
    {
        $selectStr = "SELECT email FROM " . $this->table . ';';
        $stmt = $this->db->prepare($selectStr);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return array_values($row)[0];
    }

    public function getPassword(): string
    {
        $selectStr = "SELECT password FROM " . $this->table . ';';
        $stmt = $this->db->prepare($selectStr);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return array_values($row)[0];
    }
}
