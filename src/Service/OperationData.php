<?php

declare(strict_types=1);

namespace CommissionCalculator\Service;

class OperationData
{
    public readonly float $amount;
    public readonly string $date;
    public readonly string $currency;
    public readonly int $userId;
    public readonly string $userType;
    public readonly string $userOperation;

    public function __construct(
        array $data
    ) {
        $this->validateInputRow($data);
    }

    private function validateInputRow(array $row): void
    {
        if (count($row) !== $this->getNumberDataFields()) {
            throw new \InvalidArgumentException('Operation Row has wrong number of fields');
        }

        $this->setDate($row[0]);
        $this->setUserId((int) $row[1]);
        $this->setUserType($row[2]);
        $this->setUserOperation($row[3]);
        $this->setAmount((float) $row[4]);
        $this->setCurrency($row[5]);
    }

    private function getNumberDataFields(): int
    {
        return count(get_class_vars(__CLASS__));
    }

    private function setAmount(float $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount given below zero');
        }
        $this->amount = $amount;
    }

    private function setDate(string $date): void
    {
        if (strtotime($date) === false) {
            throw new \InvalidArgumentException('Date format is unreadable');
        }
        $this->date = $date;
    }

    private function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    private function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    private function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    private function setUserOperation(string $userOperation): void
    {
        $this->userOperation = $userOperation;
    }
}
