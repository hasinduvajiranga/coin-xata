<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\ITransactionRepository;
use App\Repositories\Contracts\IAccountRepository;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    private ITransactionRepository $transactionRepository;
    private IAccountRepository $accountRepository;

    public function __construct(
        ITransactionRepository $transactionRepository,
        IAccountRepository $accountRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    public function index(): Response
    {
        // Simple analytics for now: fetch all transactions and let frontend process
        // In a real high-perf app, we'd aggregate this on DB level
        $transactions = $this->transactionRepository->all();
        $accounts = $this->accountRepository->all();

        return Inertia::render('Analytics/Index', [
            'transactions' => $transactions,
            'accounts' => $accounts,
        ]);
    }
}
