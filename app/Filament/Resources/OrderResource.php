<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\OrderResource\Pages;
use Filament\Forms\Components;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    // ✅ FORM
    public static function form(Form $form): Form
    {
        return $form->schema([
            Components\Select::make('user_id')
                ->label('Customer')
                ->relationship('user', 'name')
                ->required(),

            Components\Select::make('payment_method')
                ->options([
                    'cod' => 'Cash on Delivery',
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                ])
                ->required(),

            Components\Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                ])
                ->required(),

            Components\Select::make('status')
                ->label('Order Status')
                ->options([
                    'new' => 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])
                ->default('new')
                ->required(),

            Components\TextInput::make('grand_total')
                ->numeric()
                ->prefix('₹')
                ->required(),

            Components\Textarea::make('notes')
                ->rows(3),
        ]);
    }

    // ✅ TABLE
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer'),
                TextColumn::make('grand_total')->money('INR'),
                BadgeColumn::make('payment_status')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'new',
                        'warning' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                TextColumn::make('created_at')->dateTime()->label('Created At'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // ✅ PAGES
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
