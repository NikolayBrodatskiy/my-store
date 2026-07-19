<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CartService
{
    protected Cart $cart;

    public function getCart(): Cart
    {
        return Cart::query()
            ->when(auth()->check(), fn(Builder $q) => $q->where('user_id', auth()->id()))
            ->orWhere('storage_id', session()->getId())
            ->first() ?? Cart::create(['storage_id' => session()->getId(), 'user_id' => auth()->id()]);
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
        $cartItem->increment('count');
    }

    public function decrease(Product $product): void
    {
        /** @var CartItem $cartItem */
        $cartItem = $this->getCart()->cartItems()->firstWhere('product_id', $product->id);
        if ($cartItem->count > 1) {
            $cartItem->decrement('count');
        }
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
        $cartItem->delete();
    }

    public function mergeCarts(): void
    {
        $carts = Cart::where('user_id', auth()->id())->get();
        $newCart = Cart::create(['storage_id' => session()->getId(), 'user_id' => auth()->id()]);

        /** @var Cart $cart */
        foreach ($carts as $cart) {
            $cartItems = $cart->cartItems()->get();

            /** @var CartItem $cartItem */
            foreach ($cartItems as $cartItem) {
                /** @var Product $product */
                $product = $newCart->cartItems()
                    ->firstOrCreate(['product_id' => $cartItem->product_id], ['price' => $cartItem->price, 'count' => $cartItem->count]);

                if ($product->count < $cartItem->count) {
                    $product->count = $cartItem->count;
                    $product->save();
                }

            }
            $cart->delete();
        }
    }
}
