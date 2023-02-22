<?php

declare(strict_types=1);

namespace CommissionCalculator\Service;

class CSVIterator implements \Iterator
{
    private $file;
    private string $delimiter;
    private int $key = 0;
    private mixed $current;

    public function __construct($file, $delimiter = ',')
    {
        if (!$this->file = fopen($file, 'rb')) {
            throw new \RuntimeException('CSV file not found.');
        }
        $this->delimiter = $delimiter;
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function rewind(): void
    {
        $this->key = 0;
        rewind($this->file);
        $this->current = fgetcsv($this->file, 0, $this->delimiter);
    }

    public function valid(): bool
    {
        return (bool) $this->current === true;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function current(): mixed
    {
        return $this->current;
    }

    public function next(): void
    {
        ++$this->key;
        $this->current = fgetcsv($this->file, 0, $this->delimiter);
    }
}
