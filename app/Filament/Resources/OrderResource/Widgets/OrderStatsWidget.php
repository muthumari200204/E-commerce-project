<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count())
                ->description('Fresh orders received')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count())
                ->description('Orders being processed')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count())
                ->description('Orders shipped to customers')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Average Price', '₹' . Number::format(Order::query()->avg('grand_total') ?? 0))
                ->description('Average order value')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}