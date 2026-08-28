<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function pay(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->getKey());

            $lockedOrder->updateStatus(OrderStatus::Paid);

            $lockedOrder->orderItems()
                ->get()
                ->each(function (OrderItem $orderItem) {
                    Product::query()
                        ->whereKey($orderItem->product_id)
                        ->increment('sold_quantity', $orderItem->quantity);
                });
        });
    }
}
