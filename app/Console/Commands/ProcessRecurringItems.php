<?php

namespace App\Console\Commands;

use App\Services\RecurringService;
use Illuminate\Console\Command;

class ProcessRecurringItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coinxata:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all recurring transactions and budgets';

    /**
     * Execute the console command.
     */
    public function handle(RecurringService $recurringService)
    {
        $this->info('Starting recurring items processing...');
        
        $recurringService->processAll();
        
        $this->info('Processing completed successfully!');
    }
}
