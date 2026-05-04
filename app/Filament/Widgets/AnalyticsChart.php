<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AnalyticsChart extends ChartWidget
{
    protected static ?string $heading = 'Page Views Over Time';

    protected static ?int $sort = 2;

    public ?string $filter = '7days';

    protected function getData(): array
    {
        $days = match($this->filter) {
            'today' => 1,
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 7,
        };

        $data = Trend::model(PageView::class)
            ->between(
                start: now()->subDays($days),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            '7days' => 'Last 7 days',
            '30days' => 'Last 30 days',
            '90days' => 'Last 90 days',
        ];
    }
}
