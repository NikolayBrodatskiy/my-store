<?php

namespace App\Parents\Repositories;

use Elastic\Elasticsearch\ClientInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

abstract class ElasticsearchRepository extends Repository
{
    private readonly ClientInterface $elasticsearch;

    public function __construct()
    {
        parent::__construct();

        $this->elasticsearch = app(ClientInterface::class);
    }

    public function search(string $searchText, ?Builder $query = null): Builder
    {
        $items = $this->searchOnElasticsearch($searchText);

        return $this->applySearchResults($items, $query);
    }

    protected function searchOnElasticsearch(string $searchText): array
    {
        $items = $this->elasticsearch->search([
            'index' => $this->model->searchableAs(),
            'size' => config('services.search.max_results'),
            '_source' => false,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'multi_match' => [
                                    'fields' => $this->model->getSearchableFields(),
                                    'query' => $searchText,
                                    'fuzziness' => 'AUTO',
                                ],
                            ],
                            [
                                'match_phrase' => [
                                    'title' => [
                                        'query' => $searchText,
                                        'boost' => 40,
                                    ],
                                ],
                            ],
                        ],
                        'minimum_should_match' => 1,
                    ],
                ],
            ],
        ])->asArray();

        return $items;
    }

    private function applySearchResults(array $items, ?Builder $query = null): Builder
    {
        $ids = array_map('intval', Arr::pluck($items['hits']['hits'], '_id'));

        $query = $query ?? $this->startConditions();

        if ($ids === []) {
            //Возврат пустого результата
            return $query->whereRaw('1 = 0');
        }

        $qualifiedKey = $this->model->qualifyColumn($this->model->getKeyName());
        $query->whereIn($qualifiedKey, $ids);

        return $query;
    }
}
