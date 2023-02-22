<?php

namespace CommissionCalculator\Tests\Service\Users;

use CommissionCalculator\Service\CurrencyConverter;
use CommissionCalculator\Service\OperationData;
use CommissionCalculator\Service\OperationsRegistry;
use CommissionCalculator\Service\Users\UserBusiness;
use PHPUnit\Framework\TestCase;

class UserBusinessTest extends TestCase
{
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
     * @covers \CommissionCalculator\Service\Users\UserPrivate::deposit
     */
    public function testDepositUsesNormalCommission(): void
    {
        $data = new OperationData(1000,'2022-01-01','EUR',1,'private','deposit');
        $registry = new OperationsRegistry();
        $user = new UserBusiness($data,$registry,$this->converter);

        $commission = $user->deposit();
        self::assertEquals(0.3, $commission);
    }

    /**
     * @covers \CommissionCalculator\Service\Users\UserPrivate::withdraw
     */
    public function testWithdrawUsesNormalCommission(): void
    {
        $data = new OperationData(1000,'2022-01-01','EUR',1,'business','withdraw');
        $registry = new OperationsRegistry();
        $user = new UserBusiness($data,$registry,$this->converter);

        $commission = $user->withdraw();
        self::assertEquals(5.0, $commission);
    }
}
