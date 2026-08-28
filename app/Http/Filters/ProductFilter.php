<?php

namespace App\Http\Filters;

use App\Parents\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    public const string SEARCH = 'search';

    public const string MIN_PRICE = 'minPrice';

    public const string MAX_PRICE = 'maxPrice';
    private readonly ProductRepository $productRepository;

    public function __construct(array $queryParams, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        parent::__construct($queryParams);
    }

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::MIN_PRICE => [$this, 'minPrice'],
            self::MAX_PRICE => [$this, 'maxPrice'],
        ];
    }

    public function search(Builder $builder, $value): void
    {
        if (! config('services.search.enabled')) {
            $builder->where(function (Builder $query) use ($value) {
                $query
                    ->where('products.title', 'like', "%{$value}%")
                    ->orWhere('products.description', 'like', "%{$value}%");
            });

            return;
        }
        $this->productRepository->search($value, $builder);
    }

    public function minPrice(Builder $builder, $value): void
    {
        $builder->where('price', '>=', $value);
    }

    public function maxPrice(Builder $builder, $value): void
    {
        $builder->where('price', '<=', $value);
    }
}
