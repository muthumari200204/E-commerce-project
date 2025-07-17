<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Str;

// Form Components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;

// Table Columns
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

// Table Actions
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ActionGroup;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    TextInput::make('slug')
                        ->maxLength(255)
                        ->readOnly()
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),

                FileUpload::make('image')
                    ->image()
                    ->directory('brands')
                    ->visibility('public')
                    ->required()
                    ->visible(fn (string $operation) => $operation === 'create'),

                Placeholder::make('image_preview')
                    ->label('Image')
                    ->content(fn ($record) =>
                        $record && $record->image
                            ? '<img src="' . asset('storage/' . $record->image) . '" width="100" />'
                            : 'No image available'
                    )
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->extraAttributes(['class' => 'mt-2']),

                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true)
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->sortable(),
                ImageColumn::make('image')->label('Image'),
                IconColumn::make('is_active')->label('Active')->boolean(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->color('gray')
                        ->url(fn ($record) => route('filament.admin.resources.brands.edit', ['record' => $record]))
                        ->openUrlInNewTab(),

                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
