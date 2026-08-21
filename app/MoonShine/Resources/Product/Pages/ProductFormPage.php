<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\Models\Category;
use App\Models\Sticker;
use App\Models\Tag;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Tag\TagResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\Support\ListOf;
use Throwable;

/**
 * @extends FormPage<ProductResource>
 */
class ProductFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     * @throws Throwable
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),

                Flex::make([
                    Text::make('Title', 'title')
                        ->required(),

                    Select::make('Category', 'category_id')
                        ->options(Category::query()->pluck('title', 'id')->toArray())
                        ->required()
                        ->placeholder('Select category'),
                ]),

                Textarea::make('Description', 'description')
                    ->customAttributes([
                        'rows' => 6,
                    ]),

                Image::make('Preview Image', 'preview_image')
                    ->removable()
                    ->disk('public')
                    ->dir('images')
                    ->allowedExtensions(['jpg', 'jpeg', 'png', 'gif', 'webp']),

                Json::make('Attributes', 'attributes')
                    ->keyValue('Key', 'Value')
                    ->removable(),

                Flex::make([
                    Select::make('Sticker', 'sticker_id')
                        ->options(Sticker::query()->pluck('title', 'id')->toArray())
                        ->nullable()
                        ->placeholder('No sticker'),

                    BelongsToMany::make(
                        'Tags',
                        'tags',
                        formatted: static fn (Tag $model) => $model->title,
                        resource: TagResource::class,
                    )->valuesQuery(static fn (Builder $q) => $q->select(['id', 'title'])),
                ]),

                Flex::make([
                    Number::make('Price', 'price')
                        ->required()
                        ->min(0),

                    Number::make('Count', 'count')
                        ->min(0)
                        ->default(0),
                ]),

                Switcher::make('Published', 'is_published')
                    ->default(true),
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'preview_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sticker_id' => ['nullable', 'integer', 'exists:stickers,id'],
            'tags' => ['nullable', 'array'],
            'attributes' => ['nullable', 'array'],
            'price' => ['required', 'integer', 'min:0'],
            'count' => ['required', 'integer', 'min:0'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    /**
     * @param  FormBuilder  $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
