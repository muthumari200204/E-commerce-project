<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        return [
            Card::make('Total Orders', number_format($totalOrders))
                ->description('All-time total orders')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('success'),

            Card::make('Pending Orders', number_format($pendingOrders))
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Card::make('Cancelled Orders', number_format($cancelledOrders))
                ->description('Cancelled by user or system')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
