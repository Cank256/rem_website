<?php

namespace App\Filament\Resources\PageViewResource\Pages;

use App\Filament\Resources\PageViewResource;
use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\AnalyticsChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPageViews extends ListRecords
{
    protected static string $resource = PageViewResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AnalyticsOverview::class,
            AnalyticsChart::class,
        ];
    }
}
