<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\IAccountRepository;
use App\Models\Account;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    private IAccountRepository $accountRepository;

    public function __construct(IAccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function index(): Response
    {
        $accounts = $this->accountRepository->all();

        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'type' => 'required|in:checking,savings,credit,cash,investment',
        ]);

        $this->accountRepository->create($validated);

        return redirect()->back()->with('success', 'Account created successfully');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'balance' => 'numeric',
            'type' => 'in:checking,savings,credit,cash,investment',
        ]);

        $this->accountRepository->update($id, $validated);

        return redirect()->back()->with('success', 'Account updated successfully');
    }

    public function destroy(string $id): RedirectResponse
    {
        // Simple check: don't delete if it has transactions
        $account = $this->accountRepository->find($id);
        if ($account && $account->transactions()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete account with existing transactions');
        }

        $this->accountRepository->delete($id);

        return redirect()->back()->with('success', 'Account deleted successfully');
    }
}
