<?php

namespace App\Models;

use App\Models\Traits\HasFilter;
use App\Models\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $preview_image
 * @property int $price
 * @property int $orders_quantity
 * @property int $count
 * @property float $rating
 * @property bool $is_published
 * @property int $category_id
 * @property int|null $sticker_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Sticker|null $sticker
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $wishedBy
 * @property-read int|null $wished_by_count
 *
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static Builder<static>|Product filter(\App\Http\Filters\FilterInterface $filter)
 * @method static Builder<static>|Product newModelQuery()
 * @method static Builder<static>|Product newQuery()
 * @method static Builder<static>|Product onlyTrashed()
 * @method static Builder<static>|Product query()
 * @method static Builder<static>|Product sorted()
 * @method static Builder<static>|Product whereCategoryId($value)
 * @method static Builder<static>|Product whereCount($value)
 * @method static Builder<static>|Product whereCreatedAt($value)
 * @method static Builder<static>|Product whereDeletedAt($value)
 * @method static Builder<static>|Product whereDescription($value)
 * @method static Builder<static>|Product whereId($value)
 * @method static Builder<static>|Product whereIsPublished($value)
 * @method static Builder<static>|Product whereOrdersQuantity($value)
 * @method static Builder<static>|Product wherePreviewImage($value)
 * @method static Builder<static>|Product wherePrice($value)
 * @method static Builder<static>|Product whereRating($value)
 * @method static Builder<static>|Product whereSlug($value)
 * @method static Builder<static>|Product whereStickerId($value)
 * @method static Builder<static>|Product whereTitle($value)
 * @method static Builder<static>|Product whereUpdatedAt($value)
 * @method static Builder<static>|Product withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Product withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;
    use HasFilter;
    use HasSlug;
    use Searchable;
    use SoftDeletes;

    protected $table = 'products';

    protected $guarded = false;

    protected $with = ['category', 'sticker', 'tags'];

    protected $casts = [
        'attributes' => 'json',
    ];

    public function getSearchableFields(): array
    {
        return [
            'title^3',
            'description',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function sticker(): BelongsTo
    {
        return $this->belongsTo(Sticker::class, 'sticker_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wish', 'product_id', 'user_id')->withTimestamps();
    }

    public function scopeSorted(Builder $query): void
    {
        $sortBy = request('sort');

        $query->when($sortBy, function (Builder $query, $sortBy) {
            match ($sortBy) {
                'price' => $query->orderBy('price', 'ASC'),
                '-price' => $query->orderBy('price', 'DESC'),
                'rating' => $query->orderBy('rating', 'DESC'),
                'popularity' => $query->orderBy('orders_quantity', 'DESC'),
            };
        }, function (Builder $query) {
            $query->orderBy('orders_quantity', 'DESC');
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    //    public function previewImage()
    //    {
    //        return $this->hasOne(Attachment::class, 'id', 'preview_image')
    //            ->withDefault();
    //    }
}
