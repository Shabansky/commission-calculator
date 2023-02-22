<?php

declare(strict_types=1);

namespace CommissionCalculator\Service\Users;

class UserBusiness extends UserBase implements WithdrawDeposit
{
    private float $commissionDeposit = 0.03 / 100;
    private float $commissionWithdraw = 0.5 / 100;

    public function deposit(): float
    {
        return $this->currentOperation->amount * $this->commissionDeposit;
    }

    public function withdraw(): float
    {
        return $this->currentOperation->amount * $this->commissionWithdraw;
    }
}
