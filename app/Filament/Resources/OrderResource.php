<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Order Information')->schema([
                Group::make()->schema([
                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),

                    Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'cod' => 'Cash on Delivery',
                            'stripe' => 'Stripe',
                            'paypal' => 'PayPal',
                        ])
                        ->required(),

                    TextInput::make('grand_total')
                        ->label('Grand Total')
                        ->numeric()
                        ->prefix('INR ')
                        ->readOnly(),
                ])->columns(3),

                Repeater::make('items')
                    ->label('Items')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive(),

                        TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                            ->reactive(),

                        TextInput::make('total_amount')
                            ->numeric()
                            ->readOnly()
                            ->afterStateHydrated(function ($state, callable $set, $get) {
                                $set('total_amount', $get('quantity') * $get('unit_amount'));
                            })
                            ->dehydrated(true),
                    ])
                    ->afterStateUpdated(function (callable $set, $state) {
                        $grandTotal = collect($state)->sum('total_amount');
                        $set('grand_total', $grandTotal);
                    })
                    ->columns(4)
                    ->defaultItems(1),

                Group::make()->schema([
                    Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                        ])
                        ->required(),

                    Radio::make('status')
                        ->label('Order Status')
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->inline()
                        ->required(),
                ])->columns(2),

                Group::make()->schema([
                    Select::make('currency')
                        ->options([
                            'inr' => 'INR',
                            'usd' => 'USD',
                        ])
                        ->required(),

                    Select::make('shipping_method')
                        ->options([
                            'ups' => 'UPS',
                            'fedex' => 'FedEx',
                        ])
                        ->required(),
                ])->columns(2),

                Textarea::make('notes')
                    ->rows(3)
                    ->placeholder('Any notes...'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer')->sortable()->searchable(),
                TextColumn::make('grand_total')->label('Grand Total')->money('INR'),
                TextColumn::make('payment_method')->label('Payment'),
                BadgeColumn::make('payment_status')->colors([
                    'success' => 'paid',
                    'warning' => 'pending',
                    'danger' => 'failed',
                ]),
                TextColumn::make('currency'),
                TextColumn::make('shipping_method'),
                BadgeColumn::make('status')->colors([
                    'primary' => 'new',
                    'warning' => 'processing',
                    'info' => 'shipped',
                    'success' => 'delivered',
                    'danger' => 'cancelled',
                ]),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
