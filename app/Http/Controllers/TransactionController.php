<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ITransactionRepository;
use App\Repositories\Contracts\IAccountRepository;
use App\Repositories\Contracts\IBudgetRepository;
use App\Services\AccountingService;
use App\Models\Transaction;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TransactionController extends Controller
{
    private ITransactionRepository $transactionRepository;
    private IAccountRepository $accountRepository;
    private IBudgetRepository $budgetRepository;
    private AccountingService $accountingService;

    public function __construct(
        ITransactionRepository $transactionRepository,
        IAccountRepository $accountRepository,
        IBudgetRepository $budgetRepository,
        AccountingService $accountingService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->budgetRepository = $budgetRepository;
        $this->accountingService = $accountingService;
    }

    public function index(Request $request): Response
    {
        $filters = $request->only(['type', 'category', 'account_id', 'start_date', 'end_date']);
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        
        $transactions = $this->transactionRepository->getTransactionsWithFilters($filters, $page, $perPage);
        $accounts = $this->accountRepository->all();
        $budgets = $this->budgetRepository->all();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'total_pages' => $transactions->lastPage(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
            ],
            'accounts' => $accounts,
            'budgets' => $budgets,
            'filters' => $filters,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string',
            'transaction_date' => 'required|date',
            'type' => 'required|in:expense,income',
            'account_id' => 'nullable|uuid|exists:accounts,id',
            'budget_id' => 'nullable|uuid|exists:budgets,id',
            'description' => 'nullable|string',
            'recurring' => 'string|in:one-time,daily,weekly,monthly',
        ]);

        // Logic for encryption would go here before save if implemented in service
        $transaction = $this->transactionRepository->create($validated);
        
        // Sync account balance
        $this->accountingService->processTransaction($transaction);

        return redirect()->back()->with('success', 'Transaction added successfully');
    }

    public function destroy(string $id): RedirectResponse
    {
        $transaction = $this->transactionRepository->find($id);
        if ($transaction) {
            $this->accountingService->reverseTransaction($transaction);
            $this->transactionRepository->delete($id);
        }

        return redirect()->back()->with('success', 'Transaction deleted successfully');
    }
}
