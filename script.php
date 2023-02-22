<?php

include("./vendor/autoload.php");

use \CommissionCalculator\Service\CommissionCalculator;

$calculator = new CommissionCalculator();

try {
    $currencyData = getCurrencyDataViaURL('https://developers.paysera.com/tasks/api/currency-exchange-rates');
    $calculator->setCurrencyConverter($currencyData);
    $calculator->setOperationsDataSource(new \CommissionCalculator\Service\CSVIterator('test.csv'));
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