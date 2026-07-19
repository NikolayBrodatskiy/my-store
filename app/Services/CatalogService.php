<?php

namespace App\Services;

use App\Http\Filters\ProductFilter;
use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\Tag;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Cache;

class CatalogService
{
    /**
     * @throws BindingResolutionException
     */
    public function getCatalog(array $data, ?Section $section = null, ?Category $category = null): array
    {
        $filter = app()->make(ProductFilter::class, ['queryParams' => array_filter($data)]);

        if ($section && $category) {
            $products = $category->products();
        } elseif ($section) {
            $products = $section->products();
        } else {
            $products = Product::query();
        }

        $products = $products
            ->filter($filter)
            ->sorted()
            ->paginate(8)
            ->withQueryString();

        $tags = Cache::flexible('tags', [600, 1200], function () {
            return Tag::all();
        });
        $minPrice = Cache::flexible('minPrice', [600, 1200], function () {
            return Product::orderBy('price', 'ASC')->first()->price;
        });
        $maxPrice = Cache::flexible('maxPrice', [600, 1200], function () {
            return Product::orderBy('price', 'DESC')->first()->price;
        });

        return [$products, $tags, $minPrice, $maxPrice];
    }
}
