<?php

declare(strict_types=1);

namespace CommissionCalculator\Service\Users;

use CommissionCalculator\Service\OperationData;

class UserPrivate extends UserBase implements WithdrawDeposit
{
    private float $commissionDeposit = 0.03 / 100;
    private float $commissionWithdraw = 0.3 / 100;

    private float $commissionWithdrawOffer = 0.00;
    private float $withdrawOfferAmount = 1000.00;
    private int $withdrawOfferCount = 3;

    public function deposit(): float
    {
        return $this->currentOperation->amount * $this->commissionDeposit;
    }

    public function withdraw(): float
    {
        $previousWithdrawsInOperationWeek = $this->getOperationsForWeek();

        $amountCurrentWithdraw = $this->converter->convert($this->currentOperation->amount, $this->currentOperation->currency);
        $amountPreviousWithdraws = $this->sumAmountOperationsForWeek($previousWithdrawsInOperationWeek);

        if (
            $this->amountWithdrawsGreaterThanOffer($amountPreviousWithdraws) ||
            $this->numberWithdrawsGreaterThanOffer(count($previousWithdrawsInOperationWeek))
        ) {
            return $amountCurrentWithdraw * $this->commissionWithdraw;
        }

        $amountTotalWithdraws = $amountPreviousWithdraws + $amountCurrentWithdraw;

        if ($amountTotalWithdraws < $this->withdrawOfferAmount) {
            return $amountCurrentWithdraw * $this->commissionWithdrawOffer;
        }

        return $this->calculateTotalOverOfferAmount($amountTotalWithdraws);
    }

    private function amountWithdrawsGreaterThanOffer(float $amountWithdraws): bool
    {
        return $amountWithdraws >= $this->withdrawOfferAmount;
    }

    private function numberWithdrawsGreaterThanOffer(int $numberWithdraws): bool
    {
        return $numberWithdraws > $this->withdrawOfferCount;
    }

    private function getOperationsForWeek(): array
    {
        $operationWeek = $this->formatDateToWeekAndYear($this->currentOperation->date);

        $userWithdraws = $this->registry->getByUserIdAndOperationType($this->currentOperation->userId, 'withdraw');

        return array_filter($userWithdraws, function ($withdraw) use ($operationWeek) {
            return $this->formatDateToWeekAndYear($withdraw->date) === $operationWeek;
        });
    }

    private function formatDateToWeekAndYear(string $date): string
    {
        return date('W o', strtotime($date));
    }

    private function sumAmountOperationsForWeek(array $operationsForWeek): float
    {
        return array_sum(
            array_map(
                function (OperationData $withdraw) {
                    return $this->converter->convert($withdraw->amount, $withdraw->currency);
                },
                $operationsForWeek)
        );
    }

    private function calculateTotalOverOfferAmount(float $amountTotalWithdraws): float
    {
        $amountOverOffer = $amountTotalWithdraws - $this->withdrawOfferAmount;

        $productOffer = $this->withdrawOfferAmount * $this->commissionWithdrawOffer;
        $productNormal = $amountOverOffer * $this->commissionWithdraw;

        return $productOffer + $productNormal;
    }
}
