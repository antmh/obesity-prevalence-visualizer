<?php

declare(strict_types=1);

namespace models\database;

class EurostatRepository extends Repository
{
    public function __construct(\Sqlite3 $db)
    {
        parent::__construct(
            db: $db,
            table: 'eurostat',
            columnTypes: [
                'location' => SQLITE3_TEXT,
                'year'     => SQLITE3_INTEGER,
                'category' => SQLITE3_TEXT,
                'value'    => SQLITE3_FLOAT
            ]
        );
    }

    protected function getRows(): array
    {
        $json = $this->getJsonData();
        [
            'categories' => $categories,
            'locations'  => $locations,
            'years'      => $years
        ] = $this->getDimensions($json);
        $rows = [];
        $i = 0;
        foreach ($categories as $category) {
            foreach ($locations as $location) {
                foreach ($years as $year) {
                    if (key_exists($i, $json['value'])) {
                        $columns = [
                            $location,
                            $year,
                            $category,
                            $json['value'][$i]
                        ];
                        array_push($rows, $columns);
                    }
                    $i++;
                }
            }
        }
        return $rows;
    }

    private function getJsonData(): array
    {
        $handle = curl_init('https://ec.europa.eu/eurostat/wdds/rest/data/v2.1/json/en/sdg_02_10');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json = json_decode(curl_exec($handle), associative: true);
        return $json;
    }

    private function getDimensions(array $json): array
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
}
