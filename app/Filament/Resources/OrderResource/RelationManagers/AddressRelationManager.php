<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class AddressRelationManager extends RelationManager
{
    // This tells Filament to use $record->user->addresses
    protected static string $relationship = 'user.addresses';

    protected static ?string $title = 'Address'; // Section title
    protected static ?string $label = 'Address';
    protected static ?string $pluralLabel = 'Addresses';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('full_name')->required()->label('Full Name'),
            TextInput::make('phone')->required(),
            TextInput::make('city')->required(),
            TextInput::make('state')->required(),
            TextInput::make('zip_code')->required()->label('Zip Code'),
            TextInput::make('street_address')->required()->label('Street Address'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Full Name')->sortable()->searchable(),
                TextColumn::make('phone')->sortable()->searchable(),
                TextColumn::make('city'),
                TextColumn::make('state'),
                TextColumn::make('zip_code')->label('Zip Code'),
                TextColumn::make('street_address')->label('Street Address'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New address')
                    ->color('warning')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
