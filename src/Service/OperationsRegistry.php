<?php

declare(strict_types=1);

namespace CommissionCalculator\Service;

class OperationsRegistry
{
    private array $registry = [];

    public function __construct()
    {
    }

    public function add(OperationData $data): void
    {
        $this->registry[$data->userId][$data->userOperation][] = $data;
    }

    public function getAll(): array
    {
        return $this->registry;
    }

    public function getByUserId($userId)
    {
        return $this->registry[$userId] ?? [];
    }

    public function getByUserIdAndOperationType(int $userId, string $operationType)
    {
        return $this->registry[$userId][$operationType] ?? [];
    }
}
