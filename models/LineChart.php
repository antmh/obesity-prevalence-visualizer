<?php

declare(strict_types=1);

namespace models;

class LineChart
{
    private array $years;
    private array $dataSets;

    public function __construct(array $values, bool $showValues, bool $showYears)
    {
        $maxY = 0;
        $minX = $values[0]['year'];
        $maxX = 0;
        foreach ($values as $row) {
            if ($row['value'] > $maxY) {
                $maxY = $row['value'];
            }
            if ($row['year'] < $minX) {
                $minX = $row['year'];
            }
            if ($row['year'] > $maxX) {
                $maxX = $row['year'];
            }
        }
        $maxX = $maxX - $minX;
        $this->dataSets = [];
        foreach ($values as $row) {
            $key = implode(", ", array_diff_key($row, ['value' => null, 'year' => null]));
            $value = [
                'x' => round(($row['year'] - $minX) / $maxX * 100.0, 2),
                'y' => round($row['value'] / $maxY * 100.0, 2),
                'info' => implode(", ", $row),
            ];
            if (key_exists($key, $this->dataSets)) {
                array_push($this->dataSets[$key], $value);
            } else {
                $this->dataSets[$key] = [$value];
            }
        }
        $years = [];
        foreach ($values as $row) {
            array_push($years, $row['year']);
        }
        $this->years = array_unique($years);
        asort($this->years);
    }

    public function getYears(): array
    {
        return $this->years;
    }

    public function getDataSets(): array
    {
        return $this->dataSets;
    }
}
