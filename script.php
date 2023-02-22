<?php

include("./vendor/autoload.php");

use \CommissionCalculator\Service\CommissionCalculator;

$calculator = new CommissionCalculator();

array_shift($argv);

try {
    if(!isset($argv[0], $argv[1])) {
        throw new \RuntimeException("Missing arguments. Requires the CSV data source as the first argument and the currencies URL as the second.");
    }
    [$csvDataSource, $currencyURL] = $argv;

    $currencyData = getCurrencyDataViaURL($currencyURL);
    $calculator->setCurrencyConverter($currencyData);
    $calculator->setOperationsDataSource(new \CommissionCalculator\Service\CSVIterator($csvDataSource));
} catch (\RuntimeException $e) {
    print_r($e->getMessage() . PHP_EOL);
    exit;
}

foreach($calculator->generateCommission() as $commission) {
    print_r(number_format($commission, 2) . PHP_EOL);
}

function getCurrencyDataViaURL(string $url): bool|string
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $currencyData = curl_exec($ch);
    curl_close($ch);

    return $currencyData;
}