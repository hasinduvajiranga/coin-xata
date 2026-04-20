<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'amount',
        'amount_encrypted',
        'category',
        'transaction_date',
        'type',
        'description_encrypted',
        'account_id',
        'budget_id',
        'recurring',
        'end_date',
        'change_amount',
        'last_processed_date',
    ];

    /**
     * Get the account that owns the transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the budget that owns the transaction.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
