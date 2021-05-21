<?php

declare(strict_types=1);

namespace controllers;

use models\ {
    database\Repository,
    LineChart,
    BarChart,
    Table,
};

abstract class StatisticsController extends Controller
{
    abstract protected function getRepository(): Repository;

    abstract protected function getView(): string;

    public function index(): void
    {
        $repository = $this->getRepository();
        $columnValues = $repository->getColumnValues();
        $columns = $repository->getColumns();
        $accordion = [];
        foreach ($columnValues as $key => $val) {
            array_push($accordion, [
                'name' => $key,
                'items' => $val,
            ]);
        }
        $radioGroup = [
            'name' => 'Type',
            'default' => true,
            'items' => [
                'Bar chart',
                'Line chart',
                'Table',
            ]
        ];
        $checkboxGroup = [
            'name' => 'Selected properties',
            'items' => $columns
        ];
        $orderGroup = [
            'name' => 'Order',
            'items' => $columns
        ];
        $parameters = $this->getParameters($columns, $columnValues);
        if ($parameters['type'] === 'barChart') {
            if (in_array('Value', $parameters['selectedProperties'])) {
                $values = $repository->getAllBy(
                    $parameters['selectedProperties'],
                    $parameters['filterBy'],
                    $parameters['orderBy']
                );
                $visualization = new BarChart($values, true);
            } else {
                array_push($parameters['selectedProperties'], 'Value');
                $values = $repository->getAllBy(
                    $parameters['selectedProperties'],
                    $parameters['filterBy'],
                    $parameters['orderBy']
                );
                $visualization = new BarChart($values, false);
            }
            if ($parameters['export'] !== null) {
                if ($parameters['export'] === 'SVG') {
                    header('Content-Type: image/svg+xml');
                } else {
                    header('Content-Type: image/png');
                }
                header('Content-Disposition: attachment; filename=barchart');
                $temp = tmpfile();
                $tempName = stream_get_meta_data($temp)['uri'];
                foreach ($visualization->getXValues() as $index => $x) {
                    fwrite($temp, '"' . $visualization->getYValues()[$index] . '" ' . $x . "\n");
                }
                $proc = proc_open([
                  'gnuplot', '-e',
                  'set terminal ' . ($parameters['export'] === 'SVG' ? 'svg' : 'png') . ' size 2500, 1000;
                   set xtics rotate out;
                   set style data histogram;
                   plot "' . $tempName . '" using 2:xtic(1) notitle'
                ], [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
                echo stream_get_contents($pipes[1]);
                fclose($temp);
                return;
            }
        } elseif ($parameters['type'] === 'lineChart') {
            $showValues = true;
            if (!in_array('Value', $parameters['selectedProperties'])) {
                $showValues = false;
                array_push($parameters['selectedProperties'], 'Value');
            }
            $showYears = true;
            if (!in_array('Year', $parameters['selectedProperties'])) {
                $showYears = false;
                array_push($parameters['selectedProperties'], 'Year');
            }
            $values = $repository->getAllBy(
                $parameters['selectedProperties'],
                $parameters['filterBy'],
                $parameters['orderBy']
            );
            $visualization = new LineChart($values, $showValues, $showYears);
            if ($parameters['export'] !== null) {
                if ($parameters['export'] === 'SVG') {
                    header('Content-Type: image/svg+xml');
                } else {
                    header('Content-Type: image/png');
                }
                header('Content-Disposition: attachment; filename=linechart');
                $temp = tmpfile();
                $tempName = stream_get_meta_data($temp)['uri'];
                $dataSets = $visualization->getDatasets();
                foreach ($dataSets as $dataSet) {
                    foreach ($dataSet as $index => $point) {
                         fwrite($temp, '"' . $point['info'] . '" ' . $point['x'] . ' ' . $point['y'] . "\n");
                    }
                    fwrite($temp, "\n");
                }
                $proc = proc_open([
                    'gnuplot', '-e',
                    'set terminal ' . ($parameters['export'] === 'SVG' ? 'svg' : 'png') . ' size 1000, 1000;
                     set lmargin 20; set rmargin 20;
                     plot "' . $tempName . '"
                     using 2:($3 + 2):1 with labels notitle,
                     "" using 2:3 with linespoint notitle'
                ], [["pipe", "r"], ["pipe", "w"], ["pipe", "w"]], $pipes);
                echo stream_get_contents($pipes[1]);
                fclose($temp);
                return;
            }
        } else {
            $values = $repository->getAllBy(
                $parameters['selectedProperties'],
                $parameters['filterBy'],
                $parameters['orderBy']
            );
            if ($parameters['export'] !== null) {
                header('Content-Description: File Transfer');
                header('Content-Type: text/csv');
                $file = fopen('php://output', 'w');
                $values = $repository->getAllBy(
                    $parameters['selectedProperties'],
                    $parameters['filterBy'],
                    $parameters['orderBy']
                );
                foreach ($values as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
                return;
            }
            $visualization = new Table($values);
        }
        \views\View::render('eurostat.php', [
            'accordion' => $accordion,
            'radioGroup' => $radioGroup,
            'checkboxGroup' => $checkboxGroup,
            'orderGroup' => $orderGroup,
            $parameters['type'] => $visualization,
        ]);
    }

    private function getParameters(array $columns, array $columnValues): ?array
    {
        $type = null;
        $selectedProperties = [];
        $orderBy = [];
        $filterBy = [];
        $export = null;
        foreach ($_GET as $key => $val) {
            $key = str_replace('_', ' ', $key);
            if ($key === 'Type') {
                $type = match ($_GET['Type']) {
                    'Bar chart' => 'barChart',
                    'Line chart' => 'lineChart',
                    'Table' => 'table',
                    default => null,
                };
            } elseif ($key === 'checked') {
                foreach ($val as $checkedProperty => $checked) {
                    if (!in_array($checkedProperty, $columns) || $checked !== 'on') {
                        return null;
                    }
                    array_push($selectedProperties, $checkedProperty);
                }
            } elseif ($key === 'order') {
                foreach ($val as $orderedProperty) {
                    if (!key_exists('name', $orderedProperty)) {
                        return null;
                    }
                    if (!in_array($orderedProperty['name'], $columns)) {
                        return null;
                    }
                    if (key_exists('descending', $orderedProperty) && $orderedProperty['descending'] === 'on') {
                        $orderBy[$orderedProperty['name']] = 'desc';
                        continue;
                    } elseif (!key_exists('descending', $orderedProperty)) {
                        $orderBy[$orderedProperty['name']] = 'asc';
                        continue;
                    }
                    return null;
                }
            } elseif ($key === 'export') {
                if ($val === 'CSV' || $val === 'SVG' || $val === 'PNG') {
                    $export = $val;
                } else {
                    return null;
                }
            } elseif (in_array($key, $columns)) {
                if (!in_array($val, $columnValues[$key])) {
                    return null;
                }
                $filterBy[$key] = $val;
            } else {
                return null;
            }
        }
        if ($type === null) {
            $type = 'barChart';
        }
        if ($type === 'barChart') {
            array_push($selectedProperties, 'value');
        }
        return [
            'type' => $type,
            'selectedProperties' => $selectedProperties,
            'orderBy' => $orderBy,
            'filterBy' => $filterBy,
            'export' => $export,
        ];
    }
}
