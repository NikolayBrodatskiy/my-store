<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{

    protected $signature = 'app:install';

    protected $description = 'Installation';

    public function handle(): int
    {
        $this->call('storage:link');

        return self::SUCCESS;
    }
}
