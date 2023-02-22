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
        float $amount,
        string $date,
        string $currency,
        int $userId,
        string $userType,
        string $userOperation,
    ) {
        $this->setAmount($amount);
        $this->setDate($date);
        $this->setCurrency($currency);
        $this->setUserId($userId);
        $this->setUserType($userType);
        $this->setUserOperation($userOperation);
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
        if(strtotime($date) === false) {
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
