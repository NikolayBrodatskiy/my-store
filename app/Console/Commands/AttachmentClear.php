<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;


class AttachmentClear extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'attachment:clear';

    /**
     * The console command description.
     */
    protected $description = 'Remove dont relation attachment';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        /**
         * @var $unrelatedAttachments Collection
         */
        $unrelatedAttachments = Attachment::doesntHave('relationships')
            ->whereDate('created_at', '<', now()->subDays(1))
            ->get();

        $unrelatedAttachments->each->delete();

        return self::SUCCESS;
    }
}
