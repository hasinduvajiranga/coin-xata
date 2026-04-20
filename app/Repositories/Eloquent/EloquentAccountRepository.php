<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\Contracts\IAccountRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentAccountRepository extends BaseRepository implements IAccountRepository
{
    public function __construct(Account $model)
    {
        parent::__construct($model);
    }

    public function getAccountsWithBalances(): Collection
    {
        return $this->model->all();
    }
}
