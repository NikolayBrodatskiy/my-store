<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Order;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class DeleteUnpaidOrders implements ShouldQueue
{
    use Queueable;
    const int ALIVE_TIME_OF_UNPAID_ORDER = 2;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        $orders = Order::query()
            ->where('status', OrderStatus::Unpaid)
            ->with('orderItems.product')
            ->get();

        Log::info('[DeleteUnpaidOrders] ', [
            'connection' => config('queue.default'),
            'executed_at' => now()->toDateTimeString(),
        ]);
        foreach ($orders as $order) {
            if ($order->updated_at->diffInMinutes(Date::now()) <= self::ALIVE_TIME_OF_UNPAID_ORDER) {
                continue;
            }

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
                $product->count += $orderItem->quantity;
                $product->save();
            }

            $order->updateStatus(OrderStatus::Cancelled);
        }
    }
}
