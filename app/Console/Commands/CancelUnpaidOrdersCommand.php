<?php

namespace App\Console\Commands;

use App\Jobs\DeleteUnpaidOrders;
use Illuminate\Console\Command;

class CancelUnpaidOrdersCommand extends Command
{
    protected $signature = 'orders:cancel-unpaid';

    protected $description = 'Cancel unpaid orders older than the configured timeout';

    public function handle(DeleteUnpaidOrders $job): int
    {
        $cancelled = $job->handle();

        $this->info("Cancelled {$cancelled} unpaid order(s).");

        return self::SUCCESS;
    }
}
