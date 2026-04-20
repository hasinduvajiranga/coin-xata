<?php

namespace App\Services;

use App\Repositories\Contracts\ITransactionRepository;
use App\Repositories\Contracts\IBudgetRepository;
use App\Models\Transaction;
use App\Models\Budget;
use Carbon\Carbon;

class RecurringService
{
    private ITransactionRepository $transactionRepository;
    private IBudgetRepository $budgetRepository;
    private AccountingService $accountingService;

    public function __construct(
        ITransactionRepository $transactionRepository,
        IBudgetRepository $budgetRepository,
        AccountingService $accountingService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->budgetRepository = $budgetRepository;
        $this->accountingService = $accountingService;
    }

    /**
     * Process all recurring transactions and budgets.
     */
    public function processAll(): void
    {
        $this->processBudgets();
        $this->processTransactions();
    }

    /**
     * Auto-create budgets for the current month.
     */
    private function processBudgets(): void
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $recurringBudgets = Budget::where('is_recurring', true)->get();

        foreach ($recurringBudgets as $budget) {
            $exists = Budget::where('category', $budget->category)
                ->where('month', $currentMonth)
                ->exists();

            if (!$exists) {
                Budget::create([
                    'category' => $budget->category,
                    'category_encrypted' => $budget->category_encrypted,
                    'limit_amount' => $budget->limit_amount,
                    'limit_amount_encrypted' => $budget->limit_amount_encrypted,
                    'month' => $currentMonth,
                    'is_recurring' => true,
                ]);
            }
        }
    }

    /**
     * Auto-create transactions based on recurrence rules.
     */
    private function processTransactions(): void
    {
        $now = Carbon::now();
        $recurringTransactions = Transaction::where('recurring', '!=', 'one-time')->get();

        foreach ($recurringTransactions as $tx) {
            $lastProcessed = Carbon::parse($tx->last_processed_date ?: $tx->transaction_date);
            $nextDate = $this->calculateNextDate($lastProcessed, $tx->recurring);

            while ($nextDate->lte($now) && (!$tx->end_date || $nextDate->lte(Carbon::parse($tx->end_date)))) {
                $newTxData = $tx->toArray();
                unset($newTxData['id'], $newTxData['created_at'], $newTxData['updated_at']);
                
                $newTxData['transaction_date'] = $nextDate->toDateString();
                $newTxData['last_processed_date'] = $nextDate->toDateString();

                if ($tx->change_amount) {
                    $newTxData['amount'] = $tx->change_amount;
                    $newTxData['recurring'] = 'one-time';
                    $newTxData['change_amount'] = null;
                }

                $newTx = Transaction::create($newTxData);
                $this->accountingService->processTransaction($newTx);

                $tx->update(['last_processed_date' => $nextDate->toDateString()]);
                
                $lastProcessed = $nextDate;
                $nextDate = $this->calculateNextDate($lastProcessed, $tx->recurring);
                
                if ($tx->change_amount) break;
            }
        }
    }

    private function calculateNextDate(Carbon $date, string $frequency): Carbon
    {
        $newDate = clone $date;
        return match ($frequency) {
            'daily' => $newDate->addDay(),
            'weekly' => $newDate->addWeek(),
            'monthly' => $newDate->addMonth(),
            default => $newDate,
        };
    }
}
