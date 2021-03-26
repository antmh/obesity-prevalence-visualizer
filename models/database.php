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
            $this->getWHOData();
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
            $this->getEurostatData();
        }
    }

    private function getWHOData()
    {
        $handle = curl_init('https://ghoapi.azureedge.net/api/NCD_BMI_MEANC');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json = json_decode(curl_exec($handle), associative: true)['value'];
        $count = count($json);
        for ($chunk = 0; $chunk * self::CHUNK_SIZE < $count; $chunk++) {
            $str = 'INSERT INTO who (
                        location_type,
                        location,
                        year,
                        sex,
                        age_group,
                        value
                    ) VALUES';
            for ($group = 0; $group < self::CHUNK_SIZE && $chunk * self::CHUNK_SIZE + $group < $count; $group++) {
                if ($group !== 0) {
                    $str .= ',';
                }
                $str .= "(";
                $str .= ':location_type' . $group . ',';
                $str .= ':location'      . $group . ',';
                $str .= ':year'          . $group . ',';
                $str .= ':sex'           . $group . ',';
                $str .= ':age_group'     . $group . ',';
                $str .= ':value'         . $group;
                $str .= ')';
            }
            $str .= ';';
            $stmt = $this->sqlite->prepare($str);
            for ($group = 0; $group < self::CHUNK_SIZE && $chunk * self::CHUNK_SIZE + $group < $count; $group++) {
                $val_index = $chunk * self::CHUNK_SIZE + $group;
                $stmt->bindValue(':location_type' . $group, $json[$val_index]['SpatialDimType'], SQLITE3_TEXT);
                $stmt->bindValue(':location'      . $group, $json[$val_index]['SpatialDim'], SQLITE3_TEXT);
                $stmt->bindValue(':year'          . $group, $json[$val_index]['TimeDim'], SQLITE3_INTEGER);
                $stmt->bindValue(':sex'           . $group, $json[$val_index]['Dim1'], SQLITE3_TEXT);
                $stmt->bindValue(':age_group'     . $group, $json[$val_index]['Dim2'], SQLITE3_TEXT);
                $stmt->bindValue(':value'         . $group, $json[$val_index]['NumericValue'], SQLITE3_FLOAT);
            }
            $stmt->execute();
        }
    }

    private function getEurostatData()
    {
        $handle = curl_init('https://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/sdg_02_10');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json = json_decode(curl_exec($handle), associative: true);
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
        $i = 0;
        foreach ($categories as $category) {
            foreach ($locations as $location) {
                foreach ($years as $year) {
                    if (key_exists($i, $json['value'])) {
                        $str = 'INSERT INTO eurostat (
                                    location,
                                    year,
                                    category,
                                    value
                                ) VALUES (
                                    :location,
                                    :year,
                                    :category,
                                    :value
                                );';
                        $stmt = $this->sqlite->prepare($str);
                        $stmt->bindValue(':location', $location, SQLITE3_TEXT);
                        $stmt->bindValue(':year', $year, SQLITE3_INTEGER);
                        $stmt->bindValue(':category', $category, SQLITE3_TEXT);
                        $stmt->bindValue(':value', $json['value'][$i], SQLITE3_FLOAT);
                        $stmt->execute();
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
