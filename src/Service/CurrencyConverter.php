<?php

declare(strict_types=1);

namespace CommissionCalculator\Service;

class CurrencyConverter
{
    private string $base;
    private array $rates;

    public function __construct()
    {
    }

    public function setCurrencyData(string $currencyData): void
    {
        try {
            $currencyData = json_decode($currencyData, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException(sprintf('Currency JSON decode failed with error: %s', $e->getMessage()));
        }

        if (!isset($currencyData['base'], $currencyData['rates'])) {
            throw new \RuntimeException('Malformed currency response');
        }

        $this->base = $currencyData['base'];
        $this->rates = $currencyData['rates'];
    }

    public function convert(float $amount, string $rateCode): float
    {
        if ($rateCode === $this->base) {
            return $amount;
        }

        if (!array_key_exists($rateCode, $this->rates)) {
            throw new \InvalidArgumentException('Conversion rate not found in list of available rates.');
        }

        $convertedAmount = $amount * (1 / $this->rates[$rateCode]);

        return round($convertedAmount, 4, PHP_ROUND_HALF_UP);
    }
}
