<?php

namespace App\Observers;

use Elastic\Elasticsearch\ClientInterface;

class ElasticsearchObserver
{
    public function __construct(private ClientInterface $elasticsearchClient)
    {
        // ...
    }

    public function saved($model): void
    {
        $model->elasticSearchIndex($this->elasticsearchClient);
    }

    public function deleted($model): void
    {
        $model->elasticSearchDelete($this->elasticsearchClient);
    }
}
