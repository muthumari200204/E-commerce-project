<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';
    protected static ?string $title = 'Address';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('full_name')->required(),
            TextInput::make('phone')->required(),
            TextInput::make('city')->required(),
            TextInput::make('state')->required(),
            TextInput::make('zip_code')->required(),
            TextInput::make('street_address')->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->sortable()->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('city'),
                TextColumn::make('state'),
                TextColumn::make('zip_code'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New address'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
