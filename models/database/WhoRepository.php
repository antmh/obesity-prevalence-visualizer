<?php

declare(strict_types=1);

namespace models\database;

class WhoRepository extends Repository
{
    public function __construct(\Sqlite3 $db)
    {
        parent::__construct(
            db: $db,
            table: 'who',
            columnTypes: [
                'location type' => SQLITE3_TEXT,
                'location'      => SQLITE3_TEXT,
                'year'          => SQLITE3_INTEGER,
                'sex'           => SQLITE3_TEXT,
                'age group'     => SQLITE3_TEXT,
                'value'         => SQLITE3_FLOAT
            ]
        );
    }

    protected function getRows(): array
    {
        $json = $this->getJsonData();
        $rows = [];
        foreach (range(0, count($json) - 1) as $row) {
            if ($json[$row]['NumericValue'] !== null) {
                $columns = [
                    $json[$row]['SpatialDimType'],
                    $json[$row]['SpatialDim'],
                    $json[$row]['TimeDim'],
                    $json[$row]['Dim1'],
                    $json[$row]['Dim2'],
                    $json[$row]['NumericValue'],
                ];
                array_push($rows, $columns);
            }
        }
        return $rows;
    }

    private function getJsonData(): array
    {
        $handle = curl_init('https://ghoapi.azureedge.net/api/NCD_BMI_MEANC');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        return json_decode(curl_exec($handle), associative: true)['value'];
    }
}
