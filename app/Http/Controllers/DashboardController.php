<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\IAccountRepository;
use App\Repositories\Contracts\ITransactionRepository;
use App\Repositories\Contracts\IBudgetRepository;
use App\Services\EncryptionService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private IAccountRepository $accountRepository;
    private ITransactionRepository $transactionRepository;
    private IBudgetRepository $budgetRepository;
    private EncryptionService $encryptionService;

    public function __construct(
        IAccountRepository $accountRepository,
        ITransactionRepository $transactionRepository,
        IBudgetRepository $budgetRepository,
        EncryptionService $encryptionService
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
        $this->budgetRepository = $budgetRepository;
        $this->encryptionService = $encryptionService;
    }

    public function index(): Response
    {
        $accounts = $this->accountRepository->all();
        $recentTransactions = $this->transactionRepository->getRecentTransactions(5);
        $currentMonth = Carbon::now()->format('Y-m');
        $budgets = $this->budgetRepository->getBudgetsByMonth($currentMonth);

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyTransactions = $this->transactionRepository->getTransactionsWithFilters([
            'start_date' => $startOfMonth->toDateString(),
            'end_date' => $endOfMonth->toDateString(),
        ]);

        $monthlyIncome = $monthlyTransactions->where('type', 'income')->sum('amount');
        $monthlyExpenses = $monthlyTransactions->where('type', 'expense')->sum('amount');
        $savingsRate = $monthlyIncome > 0 ? (($monthlyIncome - $monthlyExpenses) / $monthlyIncome) * 100 : 0;

        return Inertia::render('Dashboard', [
            'accounts' => $accounts,
            'recentTransactions' => $recentTransactions,
            'budgets' => $budgets,
            'stats' => [
                'totalBalance' => (float) $accounts->sum('balance'),
                'monthlyIncome' => (float) $monthlyIncome,
                'monthlyExpenses' => (float) $monthlyExpenses,
                'savingsRate' => round(max(0, $savingsRate), 1),
            ]
        ]);
    }
}
