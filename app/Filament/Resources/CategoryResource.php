<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(fn (string $operation, $state, callable $set) =>
                            $operation === 'create' ? $set('slug', Str::slug($state)) : null
                        ),

                    TextInput::make('slug')
                        ->maxLength(255)
                        ->readOnly()
                        ->required()
                        ->unique(Category::class, 'slug', ignoreRecord: true),
                ]),

                
                FileUpload::make('image')
                    ->image()
                    ->directory('categories')
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
                    ->label('Is active')
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                
                ActionGroup::make([
                    Action::make('view')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->color('gray')
                        ->url(fn ($record) => route('filament.admin.resources.brands.edit', $record))
                        ->openUrlInNewTab(),

                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
