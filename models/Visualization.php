<?php

declare(strict_types=1);

namespace models;

use models\ {
  LineChart,
  BarChart,
  Table,
  StatisticsParameters,
  database\Repository,
};

class Visualization
{
    public static function get(StatisticsParameters $parameters, Repository $repository): LineChart|BarChart|Table
    {
        $selectedProperties = $parameters->getSelectedProperties();
        if ($parameters->getType() === 'barChart' || $parameters->getType() === 'lineChart') {
            if (in_array('Value', $selectedProperties)) {
                $showValues = true;
            } else {
                array_push($selectedProperties, 'Value');
                $showValues = false;
            }
        }
        if ($parameters->getType() === 'lineChart') {
            if (in_array('Year', $selectedProperties)) {
                $showYears = true;
            } else {
                array_push($selectedProperties, 'Year');
                $showYears = false;
            }
        }
        $values = $repository->getAllBy(
            $selectedProperties,
            $parameters->getFilterBy(),
            $parameters->getOrderBy(),
            $parameters->getPage()
        );
        return match ($parameters->getType()) {
            'barChart' => new BarChart($values, $showValues),
            'lineChart' => new LineChart($values, $showValues, $showYears),
            'table' => new Table($values),
        };
    }
}
