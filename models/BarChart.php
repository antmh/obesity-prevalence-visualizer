<?php

declare(strict_types=1);

namespace models;

class BarChart implements \JsonSerializable
{
    private array $yValues;
    private array $xValues;
    private array $xPercentages;

    public function __construct(array $values, bool $showValues)
    {
        $this->xValues = [];
        $this->xPercentages = [];
        $this->yValues = [];
        $maxY = 0;
        foreach ($values as $row) {
            if ($row['value'] > $maxY) {
                $maxY = $row['value'];
            }
        }
        foreach ($values as $row) {
            array_push($this->xPercentages, $row['value'] / $maxY * 100.0);
            array_push($this->xValues, round($row['value'], 2));
        }
        foreach ($values as $row) {
            $yValue = [];
            foreach ($row as $name => $cell) {
                if ($name === 'value' && $showValues || $name !== 'value') {
                    array_push($yValue, $cell);
                }
            }
            array_push($this->yValues, implode(", ", $yValue));
        }
    }

    public function export(string $type): void
    {
        header('Content-Type: image/' . (match ($type) {
            'SVG' => 'svg+xml',
            'PNG' => 'png',
        }));
        header('Content-Disposition: attachment; filename=barchart');
        $temp = tmpfile();
        $tempName = stream_get_meta_data($temp)['uri'];
        foreach ($this->xValues as $index => $x) {
            fwrite($temp, '"' . $this->yValues[$index] . '" ' . $x . "\n");
        }
        $proc = proc_open([
          'gnuplot', '-e',
          'set terminal ' . strtolower($type) . ' size 2500, 1000;
           set xtics rotate out;
           set style data histogram;
           plot "' . $tempName . '" using 2:xtic(1) notitle'
        ], [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        echo stream_get_contents($pipes[1]);
        fclose($temp);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'xValues' => $this->xValues,
            'yValues' => $this->yValues,
            'xPercentages' => $this->xPercentages,
        ];
    }

    public function getXValues(): array
    {
        return $this->xValues;
    }

    public function getXPercentages(): array
    {
        return $this->xPercentages;
    }

    public function getYValues(): array
    {
        return $this->yValues;
    }
}
