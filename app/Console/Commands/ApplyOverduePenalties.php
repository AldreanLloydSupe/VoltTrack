<?php

namespace App\Console\Commands;

use App\Models\Bill;
use Illuminate\Console\Command;

class ApplyOverduePenalties extends Command
{
    protected $signature = 'bills:apply-overdue-penalties';

    protected $description = 'Apply 5% daily compounded penalties to overdue bills.';

    public function handle(): int
    {
        $updated = Bill::applyDailyOverduePenalties();

        $this->info("Applied overdue penalties to {$updated} bill(s).");

        return self::SUCCESS;
    }
}

