<?php

namespace App\Repositories\Contracts;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Collection;

interface IBudgetRepository extends IBaseRepository
{
    public function getBudgetsByMonth(string $month): Collection;
}
