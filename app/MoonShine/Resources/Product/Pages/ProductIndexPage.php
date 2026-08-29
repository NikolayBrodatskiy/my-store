<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\Models\Category;
use App\Models\Sticker;
use App\Models\Tag;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Tag\TagResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<ProductResource>
 */
class ProductIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),

            Image::make('Preview Image', 'preview_image')
                ->disk('public')
                ->dir('images'),

            Text::make('Title', 'title')
                ->sortable(),

            Text::make('Category', 'category.title'),

            Text::make('Sticker', 'sticker.title'),

            BelongsToMany::make(
                'Tags',
                'tags',
                formatted: static fn (Tag $model) => $model->title,
                resource: TagResource::class,
            )->inLine(badge: true),

            Number::make('Price', 'price')
                ->sortable(),

            Number::make('Count', 'count')
                ->sortable(),

            Number::make('Rating', 'rating')
                ->sortable(),

            Switcher::make('Published', 'is_published')
                ->sortable(),

            Date::make('Created At', 'created_at')
                ->sortable(),
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Text::make('Title', 'title'),

            Select::make('Category', 'category_id')
                ->options(Category::query()->pluck('title', 'id')->toArray()),

            Select::make('Sticker', 'sticker_id')
                ->options(Sticker::query()->pluck('title', 'id')->toArray()),

            BelongsToMany::make(
                'Tags',
                'tags',
                formatted: static fn (Tag $model) => $model->title,
                resource: TagResource::class,
            )->selectMode(),

            Switcher::make('Published', 'is_published'),
        ];
    }

    /**
     * @param  TableBuilder  $component
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }
}
