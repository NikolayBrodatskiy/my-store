<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

/**
 *
 */
class WishController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $user = auth()->user();
        $products = $user->wishes()->get() ?? collect();

        return Inertia::render('Wishes/Index', [
            'wishes' => ProductResource::collection($products),
        ]);
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     */
    public function toggle(Product $product): RedirectResponse
    {
        $user = auth()->user();
        $user->wishes()->toggle([$product->id]);

        return back();
    }
}
