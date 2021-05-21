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
                'x' => $maxX === 0 ? 0 : round(($row['year'] - $minX) / $maxX * 100.0, 2),
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

    public function export(): void
    {
        header('Content-Type: image/' . (match ($type) {
            'SVG' => 'svg+xml',
            'PNG' => 'png',
        }));
        header('Content-Disposition: attachment; filename=barchart');
        $temp = tmpfile();
        $tempName = stream_get_meta_data($temp)['uri'];
        foreach ($this->dataSets as $dataSet) {
            foreach ($dataSet as $index => $point) {
                fwrite($temp, '"' . $point['info'] . '" ' . $point['x'] . ' ' . $point['y'] . "\n");
            }
            fwrite($temp, "\n");
        }
        $proc = proc_open([
          'gnuplot', '-e',
          'set terminal ' . strtolower($type) . ' size 1000, 1000;
           set lmargin 20; set rmargin 20;
           plot "' . $tempName . '"
           using 2:($3 + 2):1 with labels notitle,
           "" using 2:3 with linespoint notitle'
        ], [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        echo stream_get_contents($pipes[1]);
        fclose($temp);
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
