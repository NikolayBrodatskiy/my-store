<?php

namespace App\Parents\Repositories;

use App\Models\Product;

class ProductRepository extends ElasticsearchRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return Product::class;
    }
}
