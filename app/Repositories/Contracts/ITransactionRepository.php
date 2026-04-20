<?php

namespace App\Repositories\Contracts;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ITransactionRepository extends IBaseRepository
{
    public function getRecentTransactions(int $limit = 10): Collection;
    public function getTransactionsWithFilters(array $filters, int $page = 1, int $perPage = 10): LengthAwarePaginator;
}
