<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    public function getCart(): Cart
    {
        $sessionId = session()->getId();
        $userId = auth()->id();

        $cart = Cart::query()
            ->when($userId, fn (Builder $q) => $q->where('user_id', $userId))
            ->when(!$userId, fn (Builder $q) => $q
                ->where('storage_id', $sessionId)
                ->whereNull('user_id'))
            ->first();

        if (!$cart && $userId) {
            $cart = Cart::query()
                ->where('storage_id', $sessionId)
                ->whereNull('user_id')
                ->first();
        }

        return $cart ?? Cart::create([
            'storage_id' => $sessionId,
            'user_id' => $userId,
        ]);
    }

    public function add(Product $product): void
    {
        $cart = $this->getCart();

        $cartItem = $cart->cartItems()->updateOrCreate([
            'product_id' => $product->getKey()
        ], [
            'price' => $product->price,
        ]);
        $cartItem->increment('count');

        $cartItem->save();
    }

    public function increase(Product $product): void
    {
        $cartItem = $this->getCart()->cartItems()->firstWhere('product_id', $product->id);

        if (!$cartItem) {
            return;
        }

        $cartItem->increment('count');
    }

    public function decrease(Product $product): void
    {
        /** @var CartItem|null $cartItem */
        $cartItem = $this->getCart()->cartItems()->firstWhere('product_id', $product->id);

        if (!$cartItem || $cartItem->count <= 1) {
            return;
        }

        $cartItem->decrement('count');
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getItems(): Collection
    {
        return $this->getCart()->cartItems;
    }

    public function getTotal()
    {
        return $this->getItems()->sum(function (CartItem $item) {
            return $item->count * $item->price;
        });
    }

    public function count(){
        return $this->getItems()->sum(function (CartItem $item) {
            return $item->count;
        });
    }

    public function destroy(): void
    {
        $this->getCart()?->delete();
    }

    public function destroyItem(Product $product): void
    {
        $cartItem = $this->getCart()->cartItems()->firstWhere('product_id', $product->id);

        if (!$cartItem) {
            return;
        }

        $cartItem->delete();
    }

    public function mergeCarts(): void
    {
        $sessionId = session()->getId();
        $userId = auth()->id();

        $carts = Cart::query()
            ->where('user_id', $userId)
            ->with('cartItems')
            ->get();

        if ($carts->count() <= 1) {
            $carts->first()?->update([
                'storage_id' => $sessionId,
                'user_id' => $userId,
            ]);

            return;
        }

        $targetCart = $carts->firstWhere('storage_id', $sessionId) ?? $carts->first();

        foreach ($carts as $cart) {
            if ($cart->id === $targetCart->id) {
                continue;
            }

            foreach ($cart->cartItems as $cartItem) {
                $existing = $targetCart->cartItems()->firstWhere('product_id', $cartItem->product_id);

                if ($existing) {
                    $existing->update([
                        'count' => $existing->count + $cartItem->count,
                        'price' => $cartItem->price,
                    ]);
                } else {
                    $targetCart->cartItems()->create([
                        'product_id' => $cartItem->product_id,
                        'price' => $cartItem->price,
                        'count' => $cartItem->count,
                    ]);
                }
            }

            $cart->delete();
        }

        $targetCart->update([
            'storage_id' => $sessionId,
            'user_id' => $userId,
        ]);
    }
}
