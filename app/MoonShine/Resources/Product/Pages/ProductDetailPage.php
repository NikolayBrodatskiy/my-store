<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\Models\Tag;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Tag\TagResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends DetailPage<ProductResource>
 */
class ProductDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     *
     * @throws Throwable
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Image::make('Preview Image', 'preview_image')
                ->disk('public')
                ->dir('images'),

            Text::make('Title', 'title'),

            Text::make('Slug', 'slug'),

            Text::make('Description', 'description'),

            Json::make('Attributes', 'attributes')
                ->keyValue('Key', 'Value'),

            Text::make('Category', 'category.title'),

            Text::make('Sticker', 'sticker.title'),

            BelongsToMany::make(
                'Tags',
                'tags',
                formatted: static fn (Tag $model) => $model->title,
                resource: TagResource::class,
            )->inLine(badge: true),

            Number::make('Price', 'price'),

            Number::make('Count', 'count'),

            Number::make('Sold Quantity', 'sold_quantity'),

            Number::make('Rating', 'rating'),

            Switcher::make('Published', 'is_published'),

            Date::make('Created At', 'created_at')
                ->format('d.m.Y'),

            Date::make('Updated At', 'updated_at')
                ->format('d.m.Y'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param  TableBuilder  $component
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
