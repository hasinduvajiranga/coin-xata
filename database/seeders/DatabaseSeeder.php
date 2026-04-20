<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a User
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@coinxata.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Accounts
        $accountsData = [
            ['name' => 'Main Checking', 'type' => 'checking', 'balance' => 0],
            ['name' => 'Emergency Savings', 'type' => 'savings', 'balance' => 0],
            ['name' => 'Credit Card', 'type' => 'credit', 'balance' => 0],
        ];

        $accounts = [];
        foreach ($accountsData as $data) {
            $accounts[$data['name']] = Account::create($data);
        }

        // 3. Create Budgets for Current Month
        $currentMonth = Carbon::now()->format('Y-m');
        $budgetsData = [
            ['category' => 'Food & Dining', 'limit_amount' => 120000, 'month' => $currentMonth],
            ['category' => 'Transport', 'limit_amount' => 50000, 'month' => $currentMonth],
            ['category' => 'Shopping', 'limit_amount' => 75000, 'month' => $currentMonth],
            ['category' => 'Housing', 'limit_amount' => 200000, 'month' => $currentMonth],
        ];

        $budgets = [];
        foreach ($budgetsData as $data) {
            $budgets[$data['category']] = Budget::create($data);
        }

        // 4. Generate Past 3 Months of Transactions
        $transactions = [];
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $endDate = Carbon::now();

        // Income Simulation (Salary every month)
        $dateCursor = $startDate->copy();
        while ($dateCursor <= $endDate) {
            $transactions[] = [
                'amount' => 450000,
                'category' => 'Salary',
                'type' => 'income',
                'transaction_date' => $dateCursor->copy()->day(1)->format('Y-m-d'),
                'account_id' => $accounts['Main Checking']->id,
            ];
            $dateCursor->addMonth();
        }

        // Random Expenses over the 3 months
        $expenseCategories = [
            'Food & Dining' => [1000, 5000],
            'Transport' => [500, 2500],
            'Shopping' => [3000, 15000],
            'Housing' => [150000, 150000], // Fixed rent
            'Bills & Utilities' => [5000, 12000],
            'Entertainment' => [2000, 8000],
        ];

        for ($i = 0; $i < 60; $i++) {
            $randomDate = Carbon::now()->subDays(rand(0, 90));
            $cat = array_rand($expenseCategories);
            $amountRange = $expenseCategories[$cat];
            
            // Choose account logic
            $acct = $accounts['Main Checking']->id;
            if ($cat === 'Shopping' && rand(0,1)) {
                $acct = $accounts['Credit Card']->id;
            }

            // Assign budget if category matches and in current month
            $bId = null;
            if ($randomDate->format('Y-m') === $currentMonth && isset($budgets[$cat])) {
                $bId = $budgets[$cat]->id;
            }

            // Only 1 housing per month
            if ($cat === 'Housing') continue;

            $transactions[] = [
                'amount' => rand($amountRange[0], $amountRange[1]),
                'category' => $cat,
                'type' => 'expense',
                'transaction_date' => $randomDate->format('Y-m-d'),
                'account_id' => $acct,
                'budget_id' => $bId,
            ];
        }

        // Add proper rent entries
        $dateCursor = $startDate->copy();
        while ($dateCursor <= $endDate) {
            $transactions[] = [
                'amount' => 150000,
                'category' => 'Housing',
                'type' => 'expense',
                'transaction_date' => $dateCursor->copy()->day(5)->format('Y-m-d'),
                'account_id' => $accounts['Main Checking']->id,
                'budget_id' => ($dateCursor->format('Y-m') === $currentMonth) ? $budgets['Housing']->id : null,
            ];
            $dateCursor->addMonth();
        }

        // Create Transactions and Update Account Balances
        $accountingService = app(AccountingService::class);
        
        // Sort by date
        usort($transactions, function($a, $b) {
            return strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
        });

        foreach ($transactions as $txData) {
            $tx = Transaction::create($txData);
            // Process balance changes through the service to keep things accurate
            $accountingService->processTransaction($tx);
        }
    }
}
