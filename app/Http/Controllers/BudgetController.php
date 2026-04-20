<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\IBudgetRepository;
use App\Models\Budget;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class BudgetController extends Controller
{
    private IBudgetRepository $budgetRepository;

    public function __construct(IBudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;
    }

    public function index(Request $request): Response
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $budgets = $this->budgetRepository->getBudgetsByMonth($month);

        return Inertia::render('Budgets/Index', [
            'budgets' => $budgets,
            'currentMonth' => $month,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'limit_amount' => 'required|numeric',
            'month' => 'required|string', // YYYY-MM
            'is_recurring' => 'boolean',
        ]);

        $this->budgetRepository->create($validated);

        return redirect()->back()->with('success', 'Budget set successfully');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->budgetRepository->delete($id);
        return redirect()->back()->with('success', 'Budget deleted successfully');
    }
}
