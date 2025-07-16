<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Widgets\OrderStats; // ✅ correct widget name

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Orders'),
            'new' => Tab::make('New Orders')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new'))
                ->badge(Order::query()->where('status', 'new')->count()),
            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing'))
                ->badge(Order::query()->where('status', 'processing')->count()),
            'shipped' => Tab::make('Shipped')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'shipped'))
                ->badge(Order::query()->where('status', 'shipped')->count()),
            'delivered' => Tab::make('Delivered')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'delivered'))
                ->badge(Order::query()->where('status', 'delivered')->count()),
            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled'))
                ->badge(Order::query()->where('status', 'cancelled')->count()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class, 
        ];
    }
}
