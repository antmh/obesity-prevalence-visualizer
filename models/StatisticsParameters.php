<?php

declare(strict_types=1);

namespace models;

use function var_dump;

class StatisticsParameters
{
    private array $selectedProperties = [];
    private array $orderBy = [];
    private array $filterBy = [];
    private ?string $type = 'table';
    private ?string $export = null;
    private int $page = 0;
    private bool $valid = true;
    private bool $deletable = false;

    public function __construct(private array $columns, private array $columnValues)
    {
        foreach ($_GET as $key => $val) {
            $key = str_replace('_', ' ', $key);
            $this->valid = $this->checkType($key, $val) ?:
              $this->checkSelected($key, $val) ?:
              $this->checkOrder($key, $val) ?:
              $this->checkExport($key, $val) ?:
              $this->checkFilter($key, $val) ?:
              $this->checkPage($key, $val) ?:
              $this->checkDeletable($key, $val) ?:
              false;
            if (!$this->valid) {
                return;
            }
        }
        if (
               $this->type === 'lineChart' && $this->orderBy !== []
            || $this->type === 'barChart' && $this->export === 'CSV'
            || $this->type === 'lineChart' && $this->export === 'CSV'
            || $this->type === 'table' && ($this->export === 'PNG' || $this->export === 'SVG')
            || $this->type !== 'table' && $this->deletable
        ) {
            $this->valid = false;
        }
    }

    private function checkType(string $key, string|array $val): bool
    {
        if ($key === 'Type') {
            $this->type = match ($_GET['Type']) {
                'Bar chart' => 'barChart',
                'Line chart' => 'lineChart',
                'Table' => 'table',
                default => null,
            };
            if ($this->type === null) {
                $this->valid = false;
            }
            return true;
        }
        return false;
    }

    private function checkSelected(string $key, string|array $val): bool
    {
        if ($key === 'checked') {
            foreach ($val as $property => $checked) {
                if (in_array($property, $this->columns) && $checked === 'on') {
                    array_push($this->selectedProperties, $property);
                } else {
                    $this->valid = false;
                    return true;
                }
            }
            return true;
        }
        return false;
    }

    private function checkOrder(string $key, string|array $val): bool
    {
        if ($key === 'order') {
            foreach ($val as $property) {
                if (!key_exists('name', $property)) {
                    $this->valid = false;
                    return true;
                }
                if (!in_array($property['name'], $this->columns)) {
                    $this->valid = false;
                    return true;
                }
                if (key_exists('descending', $property) && $property['descending'] === 'on') {
                    $this->orderBy[$property['name']] = 'desc';
                    continue;
                } elseif (!key_exists('descending', $property)) {
                    $this->orderBy[$property['name']] = 'asc';
                } else {
                    $this->valid = false;
                    return true;
                }
            }
            return true;
        }
        return false;
    }

    private function checkExport(string $key, string|array $val): bool
    {
        if ($key === 'export') {
            if ($val === 'CSV' || $val === 'SVG' || $val === 'PNG') {
                $this->export = $val;
            } elseif ($val !== 'None') {
                $this->valid = false;
            }
            return true;
        }
        return false;
    }

    private function checkFilter(string $key, string|array $val): bool
    {
        if (in_array($key, $this->columns)) {
            if (in_array($val, $this->columnValues[$key])) {
                $this->filterBy[$key] = $val;
            } elseif ($val !== 'All') {
                $this->valid = false;
            }
            return true;
        }
        return false;
    }

    private function checkPage(string $key, string|array $val): bool
    {
        if ($key === 'page') {
            $this->page = intval($val);
            return true;
        }
        return false;
    }

    private function checkDeletable(string $key, string|array $val): bool
    {
        if ($key === 'deletable') {
            if ($val === '') {
                $this->deletable = true;
            } else {
                $this->valid = false;
            }
            return true;
        }
        return false;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getSelectedProperties(): array
    {
        return $this->selectedProperties;
    }

    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    public function getFilterBy(): array
    {
        return $this->filterBy;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getExport(): ?string
    {
        return $this->export;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isDeletable(): bool
    {
        return $this->deletable;
    }
}
