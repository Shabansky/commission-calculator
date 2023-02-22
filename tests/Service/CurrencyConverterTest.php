<?php

namespace CommissionCalculator\Tests\Service;

use CommissionCalculator\Service\CurrencyConverter;
use CommissionCalculator\Service\OperationData;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommissionCalculator\Service\CurrencyConverter
 */
class CurrencyConverterTest extends TestCase
{
    private CurrencyConverter $converter;

    public function setUp(): void
    {
        $currencyData = [
            'base' => 'EUR',
            'rates' => [
                'EUR' => 1,
                "JPY" => 129.53,
                "USD" => 1.1497
            ]
        ];
        $this->converter = new CurrencyConverter();
        $this->converter->setCurrencyData(json_encode($currencyData));
    }

    /**
     * @covers \CommissionCalculator\Service\CurrencyConverter::convert
     */
    public function testConversionAgainstBaseResultsInSame(): void
    {
        $amount = 400;
        $currency = 'EUR';

        self::assertEquals(400, $this->converter->convert($amount,$currency));
    }

    /**
     * @covers \CommissionCalculator\Service\CurrencyConverter::convert
     */
    public function testConversionAgainstOtherRateCalculatesProperly(): void
    {
        $amount = 3000;
        $currency = 'USD';

        self::assertEquals(2609.3764, $this->converter->convert($amount,$currency));
    }
}
