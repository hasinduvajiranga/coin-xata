<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

interface IAccountRepository extends IBaseRepository
{
    public function getAccountsWithBalances(): Collection;
}
