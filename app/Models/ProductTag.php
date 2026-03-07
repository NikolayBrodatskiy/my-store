<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 *
 *
 * @property int $product_id
 * @property int $tag_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTag whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTag whereTagId($value)
 * @mixin \Eloquent
 */
class ProductTag extends Model {
    use AsSource;

    protected $table = 'product_tag';
    protected $guarded = false;
}
