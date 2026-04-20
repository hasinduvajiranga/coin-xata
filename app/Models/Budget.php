<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'category',
        'category_encrypted',
        'limit_amount',
        'limit_amount_encrypted',
        'month',
        'is_recurring',
    ];

    /**
     * Get the transactions associated with the budget.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
