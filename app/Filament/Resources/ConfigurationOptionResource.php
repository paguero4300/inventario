<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConfigurationOptionResource\Pages;
use App\Models\ConfigurationOption;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables;
use Filament\Tables\Table;

class ConfigurationOptionResource extends Resource
{
    protected static ?string $model = ConfigurationOption::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Configurador';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Opciones de Configuración';

    protected static ?string $modelLabel = 'Opción';

    protected static ?string $pluralModelLabel = 'Opciones de Configuración';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Básica')
                    ->description('Datos principales de la opción')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Categoría')
                            ->searchable()
                            ->preload()
                            ->helperText('Opcional - para filtrar opciones por material'),

                        Select::make('parent_id')
                            ->relationship(
                                'parent',
                                'name',
                                fn($query) => $query->with('category')
                            )
                            ->label('Opción Padre')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn(ConfigurationOption $record) => $record->getFullPath(' → '))
                            ->helperText('Dejar vacío para crear una opción raíz (nivel 1)')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $parent = ConfigurationOption::find($state);
                                    if ($parent && $parent->category_id) {
                                        $set('category_id', $parent->category_id);
                                    }
                                }
                            }),

                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100)
                            ->helperText('Ej: Cedro, 4 Pulgadas, Negro Mate')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Configuración del Árbol')
                    ->description('Define el flujo de selección')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        TextInput::make('next_step_label')
                            ->label('Etiqueta del Siguiente Paso')
                            ->maxLength(100)
                            ->helperText('Ej: "Seleccione Diámetro". Dejar vacío si esta opción es el final (hoja del árbol)')
                            ->placeholder('Dejar vacío si es opción final'),

                        TextInput::make('sku_part')
                            ->label('Fragmento de SKU')
                            ->maxLength(50)
                            ->helperText('Ej: CDR, 4IN, BLK - se concatenarán para formar el SKU final')
                            ->placeholder('Opcional'),

                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Define el orden de aparición en los selectores'),

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Desactivar oculta esta opción y todas sus sub-opciones'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Padre')
                    ->default('—Raíz—')
                    ->description(
                        fn(ConfigurationOption $record) =>
                        $record->parent ? $record->getFullPath(' → ') : 'Nivel 1'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('next_step_label')
                    ->label('Siguiente Paso')
                    ->default('—Final—')
                    ->color(fn($state) => $state ? 'warning' : 'success')
                    ->icon(fn($state) => $state ? 'heroicon-m-arrow-right' : 'heroicon-m-check-circle'),

                Tables\Columns\TextColumn::make('sku_part')
                    ->label('SKU Part')
                    ->badge()
                    ->color('gray')
                    ->default('—'),

                Tables\Columns\TextColumn::make('children_count')
                    ->label('Hijos')
                    ->counts('children')
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Categoría'),

                Tables\Filters\Filter::make('roots')
                    ->label('Solo Raíces')
                    ->query(fn($query) => $query->whereNull('parent_id')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado'),
            ])
            ->recordActions([
                Action::make('add_child')
                    ->label('Agregar Hijo')
                    ->icon('heroicon-m-plus-circle')
                    ->color('success')
                    ->url(
                        fn(ConfigurationOption $record) =>
                        ConfigurationOptionResource::getUrl('create', ['parent' => $record->id])
                    ),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConfigurationOptions::route('/'),
            'create' => Pages\CreateConfigurationOption::route('/create'),
            'edit' => Pages\EditConfigurationOption::route('/{record}/edit'),
        ];
    }
}
