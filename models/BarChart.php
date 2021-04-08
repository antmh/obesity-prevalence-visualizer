<?php

declare(strict_types=1);

namespace models;

class BarChart
{
    private array $yValues;
    private array $xValues;

    public function __construct(array $values)
    {
        $this->xValues = [];
        $this->yValues = [];
        $maxY = 0;
        foreach ($values as $row) {
            if ($row['value'] > $maxY) {
                $maxY = $row['value'];
            }
        }
        foreach ($values as $row) {
            array_push($this->xValues, $row['value'] / $maxY * 100.0);
        }
        foreach ($values as $row) {
            $yValue = [];
            foreach ($row as $name => $cell) {
                if ($name !== 'value') {
                    array_push($yValue, $cell);
                }
            }
            array_push($this->yValues, implode(", ", $yValue));
        }
    }

    public function getXValues(): array
    {
        return $this->xValues;
    }

    public function getYValues(): array
    {
        return $this->yValues;
    }
}
