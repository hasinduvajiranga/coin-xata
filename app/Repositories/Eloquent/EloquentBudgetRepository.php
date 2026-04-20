<?php

namespace App\Repositories\Eloquent;

use App\Models\Budget;
use App\Repositories\Contracts\IBudgetRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentBudgetRepository extends BaseRepository implements IBudgetRepository
{
    public function __construct(Budget $model)
    {
        parent::__construct($model);
    }

    public function getBudgetsByMonth(string $month): Collection
    {
        return $this->model->where('month', $month)->get();
    }
}
