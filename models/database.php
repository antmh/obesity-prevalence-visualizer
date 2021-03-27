<?php

declare(strict_types=1);

namespace models;

class Database
{
    private \Sqlite3 $sqlite;

    private const CHUNK_SIZE = 500;

    public function __construct()
    {
        $this->sqlite = new \Sqlite3('database.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        if (!$this->tableExists('who')) {
            ini_set('memory_limit', '512M');
            set_time_limit(0);
            $this->sqlite->exec('CREATE TABLE who (
                                     location_type TEXT,
                                     location      TEXT,
                                     year          INTEGER,
                                     sex           TEXT,
                                     age_group     TEXT,
                                     value         REAL
                                 );');
            $this->saveWHOData();
        }
        if (!$this->tableExists('eurostat')) {
            ini_set('memory_limit', '512M');
            set_time_limit(0);
            $this->sqlite->exec('CREATE TABLE eurostat (
                                     location TEXT,
                                     year     INTEGER,
                                     category TEXT,
                                     value    REAL
                                 );');
            $this->saveEurostatData();
        }
    }

    private function getWHOData(): array
    {
        $handle = curl_init('https://ghoapi.azureedge.net/api/NCD_BMI_MEANC');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        return json_decode(curl_exec($handle), associative: true)['value'];
    }

    private function saveWHOData()
    {
        $json = $this->getWHOData();
        $totalRows = count($json);
        $stmt = null;
        for ($row = 0; $row < $totalRows; $row++) {
            if ($stmt === null || $stmt->done()) {
                $stmt = new InsertMultipleValuesStatement(
                    db: $this->sqlite,
                    table: 'who',
                    rowsToAdd: min(
                        self::CHUNK_SIZE,
                        $totalRows - $row
                    ),
                    columns: [
                        'location_type',
                        'location',
                        'year',
                        'sex',
                        'age_group',
                        'value'
                    ]
                );
            }
            $stmt->addValue($json[$row]['SpatialDimType'], SQLITE3_TEXT);
            $stmt->addValue($json[$row]['SpatialDim'], SQLITE3_TEXT);
            $stmt->addValue($json[$row]['TimeDim'], SQLITE3_INTEGER);
            $stmt->addValue($json[$row]['Dim1'], SQLITE3_TEXT);
            $stmt->addValue($json[$row]['Dim2'], SQLITE3_TEXT);
            $stmt->addValue($json[$row]['NumericValue'], SQLITE3_FLOAT);
        }
    }

    private function getEurostatData(): array
    {
        $handle = curl_init('https://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/sdg_02_10');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json = json_decode(curl_exec($handle), associative: true);
        return $json;
    }

    private function getEurostatDimensions(array $json): array
    {
        $categories = [];
        $locations = [];
        $years = [];
        foreach ($json['dimension'] as $dimension) {
            if ($dimension['label'] === 'bmi') {
                foreach ($json['dimension']['bmi']['category']['label'] as $category) {
                    array_push($categories, $category);
                }
            } elseif ($dimension['label'] === 'geo') {
                foreach ($json['dimension']['geo']['category']['label'] as $location) {
                    array_push($locations, $location);
                }
            } elseif ($dimension['label'] === 'time') {
                foreach ($json['dimension']['time']['category']['label'] as $year) {
                    array_push($years, $year);
                }
            }
        }
        return [
            'categories' => $categories,
            'locations'  => $locations,
            'years'      => $years
        ];
    }

    private function saveEurostatData()
    {
        $json = $this->getEurostatData();
        [
            'categories' => $categories,
            'locations'  => $locations,
            'years'      => $years
        ] = $this->getEurostatDimensions($json);
        $i = 0;
        $stmt = null;
        $rowsAdded = 0;
        $totalRows = count($json['value']);
        foreach ($categories as $category) {
            foreach ($locations as $location) {
                foreach ($years as $year) {
                    if (key_exists($i, $json['value'])) {
                        if ($stmt === null || $stmt->done()) {
                            $stmt = new InsertMultipleValuesStatement(
                                db: $this->sqlite,
                                table: 'eurostat',
                                rowsToAdd: min(
                                    self::CHUNK_SIZE,
                                    $totalRows - $rowsAdded
                                ),
                                columns: [
                                    'location',
                                    'year',
                                    'category',
                                    'value'
                                ]
                            );
                        }
                        $stmt->addValue($location, SQLITE3_TEXT);
                        $stmt->addValue($year, SQLITE3_INTEGER);
                        $stmt->addValue($category, SQLITE3_TEXT);
                        $stmt->addValue($json['value'][$i], SQLITE3_FLOAT);
                        $rowsAdded++;
                    }
                    $i++;
                }
            }
        }
    }

    private function tableExists(string $name): bool
    {
        $stmt = $this->sqlite->prepare('SELECT COUNT(*)
                                        FROM sqlite_master
                                        WHERE type=\'table\'
                                        AND name=:name');
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_NUM)[0] === 1;
    }
}
