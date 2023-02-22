<?php

declare(strict_types=1);

namespace CommissionCalculator\Service\Users;

use CommissionCalculator\Service\CurrencyConverter;
use CommissionCalculator\Service\OperationData;
use CommissionCalculator\Service\OperationsRegistry;

class UserBase
{
    protected OperationData $currentOperation;
    protected OperationsRegistry $registry;
    protected CurrencyConverter $converter;

    public function __construct(OperationData $currentOperation, OperationsRegistry $registry, CurrencyConverter $converter)
    {
        $this->currentOperation = $currentOperation;
        $this->registry = $registry;
        $this->converter = $converter;
    }
}
