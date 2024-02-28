<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my-store:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('storage:link');
        $this->call('migrate');
        return self::SUCCESS;
    }
}