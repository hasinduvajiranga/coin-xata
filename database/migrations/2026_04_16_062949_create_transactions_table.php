<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->decimal('amount', 15, 2)->default(0);
            $blueprint->text('amount_encrypted')->nullable();
            $blueprint->string('category');
            $blueprint->date('transaction_date');
            $blueprint->enum('type', ['expense', 'income'])->default('expense');
            $blueprint->text('description_encrypted')->nullable();
            $blueprint->foreignUuid('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $blueprint->foreignUuid('budget_id')->nullable()->constrained('budgets')->nullOnDelete();
            $blueprint->string('recurring')->default('one-time'); // one-time, daily, weekly, monthly
            $blueprint->date('end_date')->nullable();
            $blueprint->decimal('change_amount', 15, 2)->nullable();
            $blueprint->date('last_processed_date')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
