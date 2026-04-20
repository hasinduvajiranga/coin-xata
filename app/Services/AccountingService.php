<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\Contracts\IAccountRepository;

class AccountingService
{
    private IAccountRepository $accountRepository;

    public function __construct(IAccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Update account balance based on a new transaction.
     */
    public function processTransaction(Transaction $transaction): void
    {
        if (!$transaction->account_id) {
            return;
        }

        $amount = (float) $transaction->amount;
        $change = $transaction->type === 'expense' ? -$amount : $amount;

        $this->updateBalance($transaction->account_id, $change);
    }

    /**
     * Reverse an account balance change when a transaction is deleted.
     */
    public function reverseTransaction(Transaction $transaction): void
    {
        if (!$transaction->account_id) {
            return;
        }

        $amount = (float) $transaction->amount;
        $change = $transaction->type === 'expense' ? $amount : -$amount;

        $this->updateBalance($transaction->account_id, $change);
    }

    /**
     * Handle balance change when a transaction is updated.
     */
    public function updateTransaction(Transaction $oldTransaction, Transaction $newTransaction): void
    {
        // First reverse the old transaction
        $this->reverseTransaction($oldTransaction);
        
        // Then process the new one
        $this->processTransaction($newTransaction);
    }

    /**
     * Update the actual balance in the repository.
     */
    private function updateBalance(string $accountId, float $change): void
    {
        $account = $this->accountRepository->find($accountId);
        if ($account) {
            $newBalance = (float) $account->balance + $change;
            $this->accountRepository->update($accountId, [
                'balance' => $newBalance
            ]);
        }
    }
}
