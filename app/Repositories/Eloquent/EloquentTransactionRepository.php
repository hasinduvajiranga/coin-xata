<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\ITransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentTransactionRepository extends BaseRepository implements ITransactionRepository
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function getRecentTransactions(int $limit = 10): Collection
    {
        return $this->model->with(['account', 'budget'])
            ->orderBy('transaction_date', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTransactionsWithFilters(array $filters, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with(['account', 'budget'])
            ->orderBy('transaction_date', 'desc');

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['account_id'])) {
            $query->where('account_id', $filters['account_id']);
        }

        if (isset($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
