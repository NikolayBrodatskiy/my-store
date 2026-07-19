<?php

use App\Console\Commands\AttachmentClear;
use App\Jobs\DeleteUnpaidOrders;

Schedule::job(new DeleteUnpaidOrders())
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command(AttachmentClear::class)->daily();
