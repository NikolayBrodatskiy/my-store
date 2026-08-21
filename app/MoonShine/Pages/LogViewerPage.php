<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Attributes\Icon;
use YuriZoom\MoonShineLogViewer\Components\LogViewerComponent;

#[Icon('document-text')]
class LogViewerPage extends Page
{
    public function getTitle(): string
    {
        return __('Log viewer');
    }

    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function components(): array
    {
        return [
            LogViewerComponent::make(),
        ];
    }
}
