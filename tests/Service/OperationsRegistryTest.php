<?php

namespace CommissionCalculator\Tests\Service;

use CommissionCalculator\Service\OperationData;
use CommissionCalculator\Service\OperationsRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \CommissionCalculator\Service\OperationsRegistry
 */
class OperationsRegistryTest extends TestCase
{

    /**
     * @covers \CommissionCalculator\Service\OperationsRegistry::add
     * @covers \CommissionCalculator\Service\OperationsRegistry::getAll
     */
    public function testAddPopulatesRegistry(): void
    {
        $registry = new OperationsRegistry();
        $data = new OperationData(['2023-01-01', 1, 'private', 'deposit', 100, 'EUR']);

        self::assertCount(0, $registry->getAll());
        $registry->add($data);
        self::assertCount(1, $registry->getAll());
    }

    /**
     * @covers \CommissionCalculator\Service\OperationsRegistry::add
     * @covers \CommissionCalculator\Service\OperationsRegistry::getByUserId
     */
    public function testNonexistentUserIdReturnsEmptyArray(): void
    {
        $registry = new OperationsRegistry();
        $data = new OperationData(['2023-01-01', 1, 'private', 'deposit', 100, 'EUR']);

        $registry->add($data);
        self::assertCount(0, $registry->getByUserId(2));
    }

    /**
     * @covers \CommissionCalculator\Service\OperationsRegistry::add
     * @covers \CommissionCalculator\Service\OperationsRegistry::getByUserIdAndOperationType
     */
    public function testNonexistentOperationForUserIdReturnsEmptyArray(): void
    {
        $registry = new OperationsRegistry();

        $data = new OperationData(['2023-01-01', 1, 'private', 'deposit', 100, 'EUR']);

        $registry->add($data);
        self::assertCount(0, $registry->getByUserIdAndOperationType(2, 'withdraw'));
    }
}
