<?php

namespace App\Filament\Widgets;

use App\Models\InventoryEntry;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SessionSummaryWidget extends BaseWidget
{
    protected static ?int $sortOrder = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Active products in catalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),
            Stat::make('Total Entries', InventoryEntry::count())
                ->description('Total inventory movements')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary'),
            Stat::make('Recent Activity', InventoryEntry::where('created_at', '>=', now()->subDay())->count())
                ->description('Entries in last 24h')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
