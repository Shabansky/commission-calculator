<?php

namespace CommissionCalculator\Tests\Service;

use CommissionCalculator\Service\OperationData;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommissionCalculator\Service\OperationData
 */
class OperationDataTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function testPropertiesSetCorrectly(): void
    {
        $amount = 100;
        $date = '2023-01-01';
        $currency = 'USD';
        $userId = 1;
        $userType = 'business';
        $operation = 'withdraw';

        $data = new OperationData($amount, $date, $currency, $userId, $userType, $operation);

        self::assertEquals($amount, $data->amount);
        self::assertEquals($date, $data->date);
        self::assertEquals($currency, $data->currency);
        self::assertEquals($userId, $data->userId);
        self::assertEquals($userType, $data->userType);
        self::assertEquals($operation, $data->userOperation);
    }

    /**
     * @covers \CommissionCalculator\Service\OperationData::setAmount
     * @covers \CommissionCalculator\Service\OperationData::setDate
     * @covers \CommissionCalculator\Service\OperationData::setCurrency
     * @covers \CommissionCalculator\Service\OperationData::setUserId
     * @covers \CommissionCalculator\Service\OperationData::setUserType
     * @covers \CommissionCalculator\Service\OperationData::setUserOperation
     */
    public function testNegativeAmountThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $amount = -100;
        $date = '2023-01-01';
        $currency = 'USD';
        $userId = 1;
        $userType = 'business';
        $operation = 'withdraw';

        $data = new OperationData($amount, $date, $currency, $userId, $userType, $operation);
    }

    /**
     * @covers \CommissionCalculator\Service\OperationData::setAmount
     * @covers \CommissionCalculator\Service\OperationData::setDate
     * @covers \CommissionCalculator\Service\OperationData::setCurrency
     * @covers \CommissionCalculator\Service\OperationData::setUserId
     * @covers \CommissionCalculator\Service\OperationData::setUserType
     * @covers \CommissionCalculator\Service\OperationData::setUserOperation
     */
    public function testInvalidDateThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $amount = 100;
        $date = 'this-is-not-a-date';
        $currency = 'USD';
        $userId = 1;
        $userType = 'business';
        $operation = 'withdraw';

        $data = new OperationData($amount, $date, $currency, $userId, $userType, $operation);
    }
}
