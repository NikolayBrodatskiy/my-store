<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response = $this->get('/orders');

        $response->assertStatus(200);
    }

    public function test_paying_for_an_order_increases_the_sold_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['sold_quantity' => 10]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Unpaid,
        ]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->actingAs($user)
            ->post(route('orders.pay', $order))
            ->assertRedirect();

        $this->assertSame(OrderStatus::Paid, $order->refresh()->status);
        $this->assertSame(13, $product->refresh()->sold_quantity);
    }

    public function test_an_order_cannot_increase_the_sold_quantity_twice(): void
    {
        $product = Product::factory()->create(['sold_quantity' => 10]);
        $order = Order::factory()->create(['status' => OrderStatus::Unpaid]);
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
        $service = app(OrderService::class);

        $service->pay($order);

        $secondPaymentWasRejected = false;

        try {
            $service->pay($order);
        } catch (Exception) {
            $secondPaymentWasRejected = true;
        }

        $this->assertTrue($secondPaymentWasRejected);
        $this->assertSame(13, $product->refresh()->sold_quantity);
    }
}
