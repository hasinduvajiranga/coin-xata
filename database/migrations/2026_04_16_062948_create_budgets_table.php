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
        Schema::create('budgets', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->string('category');
            $blueprint->text('category_encrypted')->nullable();
            $blueprint->decimal('limit_amount', 15, 2)->default(0);
            $blueprint->text('limit_amount_encrypted')->nullable();
            $blueprint->string('month'); // format: YYYY-MM
            $blueprint->boolean('is_recurring')->default(false);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
