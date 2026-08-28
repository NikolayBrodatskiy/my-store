<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Parents\Repositories\ProductRepository;
use Elastic\Elasticsearch\ClientInterface;
use Mockery;
use Tests\TestCase;

class ElasticsearchSearchTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ClientInterface::class, Mockery::mock(ClientInterface::class));
    }

    public function test_product_exposes_only_searchable_fields_for_indexing(): void
    {
        $product = new Product([
            'id' => 12,
            'title' => 'Зелёный чай',
            'description' => 'Китайский листовой чай',
            'price' => 500,
        ]);

        $this->assertSame([
            'id' => 12,
            'title' => 'Зелёный чай',
            'description' => 'Китайский листовой чай',
        ], $product->toSearchableArray());

        $this->assertSame('russian', $product->getSearchableProperties()['title']['analyzer']);
        $this->assertSame('russian', $product->getSearchableProperties()['description']['analyzer']);
    }

    public function test_no_elasticsearch_hits_produce_an_empty_database_query(): void
    {
        $repository = new class extends ProductRepository
        {
            protected function searchOnElasticsearch(string $searchText): array
            {
                return ['hits' => ['hits' => []]];
            }
        };

        $query = $repository->search('missing', Product::query());

        $this->assertStringContainsString('1 = 0', $query->toSql());
    }
}
