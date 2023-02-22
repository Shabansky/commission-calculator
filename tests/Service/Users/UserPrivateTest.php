<?php

namespace CommissionCalculator\Tests\Service\Users;

use CommissionCalculator\Service\CurrencyConverter;
use CommissionCalculator\Service\OperationData;
use CommissionCalculator\Service\OperationsRegistry;
use CommissionCalculator\Service\Users\UserPrivate;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommissionCalculator\Service\Users\UserPrivate
 */
class UserPrivateTest extends TestCase
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
     * @covers \CommissionCalculator\Service\Users\UserPrivate::deposit
     */
    public function testDepositUsesNormalCommission(): void
    {
        $data = new OperationData(1000,'2022-01-01','EUR',1,'private','deposit');
        $registry = new OperationsRegistry();
        $user = new UserPrivate($data,$registry,$this->converter);

        $commission = $user->deposit();
        self::assertEquals(0.3, $commission);
    }

    /**
     * @covers \CommissionCalculator\Service\Users\UserPrivate::withdraw
     */
    public function testPrivateWithdrawUnderOfferAmountUsesOfferCommission(): void
    {
        $data = new OperationData(800,'2022-01-01','EUR',1,'private','withdraw');
        $registry = new OperationsRegistry();
        $user = new UserPrivate($data,$registry,$this->converter);

        $commission = $user->withdraw();
        self::assertEquals(0.0, $commission);
    }

    /**
     * @covers \CommissionCalculator\Service\Users\UserPrivate::withdraw
     */
    public function testPrivateWithdrawOverOfferAmountUsesMixedCommission(): void
    {
        $data = new OperationData(2000,'2022-01-01','EUR',1,'private','withdraw');
        $registry = new OperationsRegistry();
        $user = new UserPrivate($data,$registry,$this->converter);

        $commission = $user->withdraw();
        self::assertEquals(3.0, $commission);
    }

    /**
     * @covers \CommissionCalculator\Service\Users\UserPrivate::withdraw
     */
    public function testFirstThreeWithdrawsWeeklyUnderOfferAmountUseOfferCommission(): void
    {
        $data = new OperationData(200,'2022-01-01','EUR',1,'private','withdraw');
        $registry = new OperationsRegistry();
        $registry->add(new OperationData(400,'2022-01-01','EUR',1,'private','withdraw'));
        $registry->add(new OperationData(400,'2022-01-01','EUR',1,'private','withdraw'));

        $user = new UserPrivate($data,$registry,$this->converter);

        $commission = $user->withdraw();
        self::assertEquals(0.0, $commission);
    }

    /**
     * @covers \CommissionCalculator\Service\Users\UserPrivate::withdraw
     */
    public function testFirstThreeWithdrawsWeeklyOverOfferAmountUseMixedCommission(): void
    {
        $data = new OperationData(600,'2022-01-01','EUR',1,'private','withdraw');
        $registry = new OperationsRegistry();
        $registry->add(new OperationData(400,'2022-01-01','EUR',1,'private','withdraw'));
        $registry->add(new OperationData(400,'2022-01-01','EUR',1,'private','withdraw'));

        $user = new UserPrivate($data,$registry,$this->converter);

        $commission = $user->withdraw();
        self::assertEquals(1.2, $commission);
    }
}
