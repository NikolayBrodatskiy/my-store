<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\FilterRequest;
use App\Http\Resources\Product\ProductCardResource;
use App\Http\Resources\Section\SectionWithCategoriesResource;
use App\Models\Category;
use App\Models\Section;
use App\Services\CatalogService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class CatalogController extends Controller
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {

        $this->catalogService = $catalogService;
    }

    /**
     * @throws BindingResolutionException
     */
    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        [$products, $tags, $minPrice, $maxPrice] = $this->catalogService->getCatalog($data);

        return Inertia::render('Catalog/Index', [
            'sort' => $request->sort ?? 'popularity',
            'search' => $request->search ?? '',
            'products' => ProductCardResource::collection($products),
            'tags' => $tags,
            'minPrice' => $request->minPrice ? (int)$request->minPrice : $minPrice,
            'maxPrice' => $request->maxPrice ? (int)$request->maxPrice : $maxPrice
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function sectionIndex(FilterRequest $request, Section $section)
    {
        $data = $request->validated();
        [$products, $tags, $minPrice, $maxPrice] = $this->catalogService->getCatalog($data, $section);

        return Inertia::render('Catalog/Index', [
            'sort' => $request->sort ?? '',
            'search' => $request->search ?? '',
            'products' => ProductCardResource::collection($products),
            'tags' => $tags,
            'minPrice' => $request->minPrice ?? $minPrice,
            'maxPrice' => $request->maxPrice ?? $maxPrice,
        ]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function categoryIndex(FilterRequest $request, Section $section, Category $category)
    {
        $data = $request->validated();
        [$products, $tags, $minPrice, $maxPrice] = $this->catalogService->getCatalog($data, $section, $category);

        return Inertia::render('Catalog/Index', [
            'sort' => $request->sort ?? '',
            'search' => $request->search ?? '',
            'products' => ProductCardResource::collection($products),
            'tags' => $tags,
            'minPrice' => $request->minPrice ?? $minPrice,
            'maxPrice' => $request->maxPrice ?? $maxPrice,
        ]);
    }

    public function navigation()
    {
        return Cache::flexible('navigation', [600, 1200], function () {
            $section = Section::with('categories')->get();

            return SectionWithCategoriesResource::collection($section);
        });
    }
}
