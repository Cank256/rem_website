<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use App\Models\VisitorSession;
use App\Models\AnalyticsEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->can('view_analytics_dashboard') ?? false;
    }

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $last7Days = now()->subDays(7);
        $last30Days = now()->subDays(30);

        return [
            Stat::make('Total Page Views (Today)', PageView::where('created_at', '>=', $today)->count())
                ->description('Page views today')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success')
                ->chart($this->getPageViewsChart(7)),
            
            Stat::make('Unique Visitors (7 Days)', VisitorSession::where('created_at', '>=', $last7Days)->count())
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Total Page Views (30 Days)', PageView::where('created_at', '>=', $last30Days)->count())
                ->description('Last 30 days')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
            
            Stat::make('Avg. Session Duration', $this->getAverageSessionDuration())
                ->description('Average time per session')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }

    protected function getPageViewsChart(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $data[] = PageView::whereDate('created_at', $date)->count();
        }
        return $data;
    }

    protected function getAverageSessionDuration(): string
    {
        $avgSeconds = VisitorSession::where('created_at', '>=', now()->subDays(7))
            ->avg('total_duration') ?? 0;
        
        $minutes = floor($avgSeconds / 60);
        $seconds = $avgSeconds % 60;
        
        return sprintf('%dm %ds', $minutes, $seconds);
    }
}
