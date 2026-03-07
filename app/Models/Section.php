<?php

namespace App\Models;

use Database\Factories\SectionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read Collection<int, \App\Models\Category> $parentCategories
 * @property-read int|null $parent_categories_count
 * @property-read Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\SectionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Section newModelQuery()
 * @method static Builder<static>|Section newQuery()
 * @method static Builder<static>|Section query()
 * @method static Builder<static>|Section whereCreatedAt($value)
 * @method static Builder<static>|Section whereId($value)
 * @method static Builder<static>|Section whereSlug($value)
 * @method static Builder<static>|Section whereTitle($value)
 * @method static Builder<static>|Section whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Section extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = false;

    public function products(): HasManyThrough
    {
         return $this->hasManyThrough(Product::class, Category::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'section_id', 'id');
    }

    public function parentCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'section_id', 'id')
            ->whereNull('parent_id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
