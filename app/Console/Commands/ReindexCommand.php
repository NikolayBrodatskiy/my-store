<?php

namespace App\Console\Commands;

use App\Models\Product;
use Elastic\Elasticsearch\ClientInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recreate Elasticsearch indices and index searchable data';

    public function __construct(
        protected readonly ClientInterface $elasticsearch,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Reindexing searchable models');

        collect([
            Product::class,
        ])->each(fn (string $className) => $this->reindex($className));

        $this->newLine(2);
        $this->info('Done');

        return self::SUCCESS;
    }

    /**
     * @param  class-string  $className
     */
    private function reindex(string $className): void
    {
        /** @var Product $model */
        $model = new $className;
        $index = $model->searchableAs();

        $this->newLine();
        $this->info("Recreating index [{$index}]");

        $this->elasticsearch->indices()->delete([
            'index' => $index,
            'ignore_unavailable' => true,
        ]);

        $this->elasticsearch->indices()->create([
            'index' => $index,
            'body' => [
                'mappings' => [
                    'dynamic' => false,
                    'properties' => $model->getSearchableProperties(),
                ],
            ],
        ]);

        $this->info("Indexing {$className}");

        $this->withProgressBar($className::query()->cursor(), function (Product $model) {
            $model->elasticsearchIndex($this->elasticsearch);
        });

        $this->elasticsearch->indices()->refresh(['index' => $index]);
    }
}
