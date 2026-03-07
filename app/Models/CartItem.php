<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $price
 * @property int $count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Cart $cart
 * @property-read \App\Models\Product $product
 * @method static Builder<static>|CartItem newModelQuery()
 * @method static Builder<static>|CartItem newQuery()
 * @method static Builder<static>|CartItem query()
 * @method static Builder<static>|CartItem whereCartId($value)
 * @method static Builder<static>|CartItem whereCount($value)
 * @method static Builder<static>|CartItem whereCreatedAt($value)
 * @method static Builder<static>|CartItem whereId($value)
 * @method static Builder<static>|CartItem wherePrice($value)
 * @method static Builder<static>|CartItem whereProductId($value)
 * @method static Builder<static>|CartItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CartItem extends Model
{

    protected $table = 'cart_items';

    protected $with = ['product'];

    protected $fillable = [
        'id',
        'cart_id',
        'product_id',
        'price',
        'count',
        'created_at',
        'updated_at'
    ];

    public function total(): string
    {
        $total = $this->price * $this->count;

        return number_format($total, 2);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
