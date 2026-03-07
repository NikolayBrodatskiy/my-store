<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker defaultSort(string $column, string $direction = 'asc')
 * @method static \Database\Factories\StickerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker filters(?mixed $kit = null, ?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker filtersApplySelection($class)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sticker withoutTrashed()
 * @mixin \Eloquent
 */
class Sticker extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stickers';
    protected $guarded = false;

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'sticker_id', 'id');
    }
}
