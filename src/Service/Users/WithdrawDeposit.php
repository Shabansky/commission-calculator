<?php

declare(strict_types=1);

namespace CommissionCalculator\Service\Users;

interface WithdrawDeposit
{
    public function withdraw(): float;

    public function deposit(): float;
}
