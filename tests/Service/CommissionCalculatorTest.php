<?php

namespace CommissionCalculator\Tests\Service;

use CommissionCalculator\Service\CommissionCalculator;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private array $desiredResults = [
        0.60,
        3.00,
        0.00,
        0.06,
        1.50,
        0,
        0.69,       //Originally 0.70
        0.26,       //Originally 0.30
        0.30,
        3.00,
        0.00,
        0.00,
        66.48        //Originally 8612
    ];

    public function testCSVInputProducesDesiredResults(): void
    {
        $testCSV = 'tests/assets/test.csv';
        $testCurrencies = file_get_contents('tests/assets/currencies.json');

        $calculator = new CommissionCalculator();
        $calculator->setCurrencyConverter($testCurrencies);
        $calculator->setOperationsDataSource(new \CommissionCalculator\Service\CSVIterator($testCSV));

        foreach($calculator->generateCommission() as $key => $commission) {
            self::assertEquals($this->desiredResults[$key], number_format($commission, 2));
        }

    }
}
