<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('New Orders', Order::where('status', 'new')->count()),
            Card::make('Order Processing', Order::where('status', 'processing')->count()),
            Card::make('Order Shipped', Order::where('status', 'shipped')->count()),
            Card::make('Average Price', '₹' . number_format(Order::avg('grand_total'), 2)),
        ];
    }
}
