<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Tag;

use App\Models\Tag;
use App\MoonShine\Resources\Tag\Pages\TagDetailPage;
use App\MoonShine\Resources\Tag\Pages\TagFormPage;
use App\MoonShine\Resources\Tag\Pages\TagIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;

/**
 * @extends ModelResource<Tag, TagIndexPage, TagFormPage, TagDetailPage>
 */
#[Icon('tag')]
#[Group('catalog', 'catalog', translatable: true)]
#[Order(2)]
class TagResource extends ModelResource
{
    protected string $model = Tag::class;

    protected string $title = 'Tags';

    protected string $column = 'title';

    protected function pages(): array
    {
        return [
            TagIndexPage::class,
            TagFormPage::class,
            TagDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'id',
            'title',
        ];
    }
}
