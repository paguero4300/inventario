<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\ConfigurationOption;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
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
                TextColumn::make('stock_actual')
                    ->label('Stock Actual')
                    ->getStateUsing(fn($record) => $record->getCurrentStock())
                    ->numeric(decimalPlaces: 2)
                    ->sortable(query: function ($query, $direction) {
                        // Custom sort by calculating stock
                        return $query; // Can't sort calculated fields easily
                    })
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'warning')),
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
                    ->modalContent(fn($record) => view('filament.modals.product-detail', ['product' => $record])),
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
