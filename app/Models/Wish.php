<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wish whereUserId($value)
 * @mixin \Eloquent
 */
class Wish extends Model
{
    protected $table = 'wish';
    protected $guarded = [];


    // public static function getContent(): Collection
    // {
    //     return auth()->user()->whishlist;
    // }

    // public static function moveToCart(Product $product): void
    // {
    //     auth()->user()->cart()->attach($product->id, ['quantity' => 1]);
    //     auth()->user()->whishlist()->detach($product->id);
    // }

    // public static function empty(): void
    // {
    //     auth()->user()->whishlist()->detach();
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
