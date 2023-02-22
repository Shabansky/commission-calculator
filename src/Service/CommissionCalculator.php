<?php

declare(strict_types=1);

namespace CommissionCalculator\Service;

class CommissionCalculator
{
    private OperationsRegistry $operationsRegistry;
    private CurrencyConverter $currencyConverter;
    private \Iterator $operationsDataSource;

    public function __construct()
    {
        $this->setOperationsRegistry();
    }

    public function setOperationsRegistry(): void
    {
        $this->operationsRegistry = new OperationsRegistry();
    }

    public function setCurrencyConverter(bool|string $converterData): void
    {
        if ($converterData === false) {
            throw new \RuntimeException('Currencies source could not be opened.');
        }

        $this->currencyConverter = new CurrencyConverter();
        $this->currencyConverter->setCurrencyData($converterData);
    }

    public function setOperationsDataSource(\Iterator $dataIterator): void
    {
        $this->operationsDataSource = $dataIterator;
    }

    /**
     * @throws \RuntimeException
     */
    public function generateCommission(): \Generator
    {
        foreach ($this->operationsDataSource as $row) {
            try {
                $data = new OperationData($row);
                $commission = $this->getCommissionFromOperation($data);
                $this->operationsRegistry->add($data);
                yield $commission;
            } catch (\InvalidArgumentException) {
                continue;
            }
        }
    }

    private function getCommissionFromOperation(OperationData $data): float
    {
        $className = '\CommissionCalculator\Service\Users\User'.ucfirst($data->userType);
        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Nonexistent class called');
        }

        $methodName = $data->userOperation;
        if (!method_exists($className, $methodName)) {
            throw new \InvalidArgumentException('Nonexistent method called for user class');
        }

        $user = new $className($data, $this->operationsRegistry, $this->currencyConverter);

        return $user->$methodName();
    }
}
