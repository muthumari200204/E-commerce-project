<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Filament\Resources\OrderResource;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5) // Limit added to match pagination size
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('INR'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'gray',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('M d, Y H:i:s'),
            ])
            ->actions([
                Action::make('View')
                    ->url(fn(Order $record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
                    ->color('info'),
            ]);
    }
}
