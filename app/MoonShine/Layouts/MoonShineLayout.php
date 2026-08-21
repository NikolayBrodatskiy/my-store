<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\LogViewerPage;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Role\RoleResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\ColorManager\ColorManager;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;

final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn () => __('Catalog'), [
                MenuItem::make(ProductResource::class),
                MenuItem::make(TagResource::class),
            ]),
            MenuGroup::make(static fn () => __('moonshine::ui.resource.system'), [
                MenuItem::make(UserResource::class),
                MenuItem::make(RoleResource::class),
            ]),
            MenuItem::make(LogViewerPage::class),
        ];
    }

    /**
     * @param  ColorManager  $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
