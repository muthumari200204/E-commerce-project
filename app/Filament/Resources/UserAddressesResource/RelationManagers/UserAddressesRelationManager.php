<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;

class UserAddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'userAddresses';

    protected static ?string $title = 'User Addresses';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('address_line')
                    ->label('Address Line')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('postal_code')
                    ->label('Postal Code')
                    ->sortable(),

                TextColumn::make('country')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
