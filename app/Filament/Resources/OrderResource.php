<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\UserAddressesRelationManager;
use Filament\Forms\Components\{Group, Section, Select, TextInput, Textarea, Repeater, Radio};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\Filter;
use Filament\Tables\Enums\FiltersLayout;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Order Information')->schema([
                Group::make()->schema([
                    Select::make('user_id')->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),

                    Select::make('payment_method')
                        ->options([
                            'cod' => 'Cash on Delivery',
                            'stripe' => 'Stripe',
                            'paypal' => 'PayPal',
                        ])
                        ->required(),

                    TextInput::make('grand_total')->numeric()->prefix('₹')->readOnly(),
                ])->columns(3),

                Repeater::make('items')->relationship()->schema([
                    Select::make('product_id')->relationship('product', 'name')->required(),
                    TextInput::make('quantity')->numeric()->default(1)->required()->reactive(),
                    TextInput::make('unit_amount')->numeric()->required()->reactive(),
                    TextInput::make('total_amount')->numeric()->readOnly()
                        ->afterStateHydrated(fn ($state, $set, $get) =>
                            $set('total_amount', $get('quantity') * $get('unit_amount')))
                        ->dehydrated(true),
                ])->afterStateUpdated(fn ($set, $state) =>
                    $set('grand_total', collect($state)->sum('total_amount')))
                    ->columns(4),

                Group::make()->schema([
                    Select::make('payment_status')->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])->required(),

                    Radio::make('status')->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])->inline()->required(),
                ])->columns(2),

                Group::make()->schema([
                    Select::make('currency')->options(['inr' => 'INR', 'usd' => 'USD'])->required(),
                    Select::make('shipping_method')->options(['ups' => 'UPS', 'fedex' => 'FedEx'])->required(),
                ])->columns(2),

                Textarea::make('notes')->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer')->sortable()->searchable(),
                TextColumn::make('grand_total')->label('Grand Total')->money('INR'),
                TextColumn::make('payment_method')->label('Payment Method'),
                BadgeColumn::make('payment_status')->label('Payment Status')->color(fn ($state) => match ($state) {
                    'paid' => 'success',
                    'pending' => 'warning',
                    'failed' => 'danger',
                }),
                TextColumn::make('currency')->label('Currency'),
                TextColumn::make('shipping_method')->label('Shipping Method'),
                BadgeColumn::make('status')->label('Status')->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        'new' => 'primary',
                        'processing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->label('Created')->dateTime(),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filters([
                Filter::make('New')->query(fn ($query) => $query->where('status', 'new')),
                Filter::make('Processing')->query(fn ($query) => $query->where('status', 'processing')),
                Filter::make('Shipped')->query(fn ($query) => $query->where('status', 'shipped')),
                Filter::make('Delivered')->query(fn ($query) => $query->where('status', 'delivered')),
                Filter::make('Cancelled')->query(fn ($query) => $query->where('status', 'cancelled')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            UserAddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    // ✅ Add this to show widgets (like OrderStats) inside OrderResource pages
   public static function getWidgets(): array
{
    return [
        \App\Filament\Resources\OrderResource\Widgets\OrderStats::class,
    ];
}
}
