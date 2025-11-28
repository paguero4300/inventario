<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\ConfigurationOption;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('dimensions')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('viewDetail')
                    ->label('Ver Detalle')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn($record) => "Detalle: {$record->name}")
                    ->modalWidth('2xl')
                    ->infolist(fn($record): Infolist => Infolist::make([
                        Section::make('Información Básica')
                            ->schema([
                                TextEntry::make('category.name')
                                    ->label('Categoría'),
                                TextEntry::make('sku')
                                    ->label('SKU')
                                    ->copyable(),
                                TextEntry::make('name')
                                    ->label('Nombre'),
                                TextEntry::make('is_active')
                                    ->label('Estado')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state ? 'Activo' : 'Inactivo')
                                    ->color(fn($state) => $state ? 'success' : 'danger'),
                            ])->columns(2),

                        Section::make('Configuración del Producto')
                            ->schema([
                                TextEntry::make('configuration')
                                    ->label('')
                                    ->formatStateUsing(function ($record) {
                                        if (!$record->specifications || empty($record->specifications)) {
                                            return 'Sin configuración';
                                        }

                                        $config = [];
                                        for ($i = 0; $i <= 4; $i++) {
                                            $key = "config_level_{$i}";
                                            if (isset($record->specifications[$key])) {
                                                $optionId = $record->specifications[$key];
                                                $option = ConfigurationOption::find($optionId);
                                                if ($option) {
                                                    $levelLabel = $i === 0 ? 'Material' : "Nivel {$i}";
                                                    $config[] = "**{$levelLabel}:** {$option->name}" .
                                                        ($option->sku_part ? " ({$option->sku_part})" : "");
                                                }
                                            }
                                        }

                                        return implode("\n\n", $config);
                                    })
                                    ->markdown()
                                    ->columnSpanFull(),
                            ])
                            ->visible(fn($record) => !empty($record->specifications)),

                        Section::make('Detalles Adicionales')
                            ->schema([
                                TextEntry::make('dimensions')
                                    ->label('Dimensiones')
                                    ->placeholder('No especificado'),
                                TextEntry::make('color')
                                    ->label('Color')
                                    ->placeholder('No especificado'),
                                TextEntry::make('notes')
                                    ->label('Notas')
                                    ->placeholder('Sin notas')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Section::make('Información del Sistema')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Creado')
                                    ->dateTime('d/m/Y H:i'),
                                TextEntry::make('updated_at')
                                    ->label('Actualizado')
                                    ->dateTime('d/m/Y H:i'),
                            ])->columns(2)
                            ->collapsed(),
                    ])),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
