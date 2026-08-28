<?php

namespace App\Models\Traits;

use App\Observers\ElasticsearchObserver;
use Elastic\Elasticsearch\ClientInterface;
use Illuminate\Support\Arr;

trait Searchable
{
    public function elasticsearchIndex(ClientInterface $elasticsearchClient): void
    {
        $elasticsearchClient->index([
            'index' => $this->searchableAs(),
            'id' => $this->getKey(),
            'body' => $this->toSearchableArray(),
        ]);
    }

    public function elasticsearchDelete(ClientInterface $elasticsearchClient): void
    {
        $elasticsearchClient->delete([
            'index' => $this->searchableAs(),
            'id' => $this->getKey(),
        ]);
    }

    public function searchableAs(): string
    {
        return config('services.search.index_prefix').$this->getTable();
    }

    public function toSearchableArray(): array
    {
        $fields = array_map(
            static fn (string $field) => str($field)->before('^')->toString(),
            $this->getSearchableFields(),
        );

        return ['id' => $this->getKey()] + Arr::only($this->attributesToArray(), $fields);
    }

    public function getSearchableProperties(): array
    {
        $properties = ['id' => ['type' => 'long']];

        foreach ($this->getSearchableFields() as $field) {
            $field = str($field)->before('^')->toString();
            $properties[$field] = [
                'type' => 'text',
                'analyzer' => 'russian',
            ];
        }

        return $properties;
    }

    public static function bootSearchable(): void
    {
        if (config('services.search.enabled')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    abstract public function getSearchableFields(): array;
}
