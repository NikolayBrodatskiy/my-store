<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{

    protected $signature = 'app:db-refresh';

    protected $description = 'Refresh with seeds';

    public function handle(): int
    {
        if (app()->isProduction()) {
            return self::FAILURE;
        }

        Storage::deleteDirectory('public');

        $this->call('migrate:fresh', ['--seed' => true]);

        return self::SUCCESS;
    }
}
