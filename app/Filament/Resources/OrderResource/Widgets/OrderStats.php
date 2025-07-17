<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderStats extends BaseWidget
{
    protected function getCards(): array
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        return [
            Card::make('Total Orders', number_format($totalOrders)),
            Card::make('Pending Orders', number_format($pendingOrders)),
            Card::make('Cancelled Orders', number_format($cancelledOrders)),
        ];
    }
}
