<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\Location;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class StockReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.stock-report';



    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Reporte de Stock';
    }

    public function getTitle(): string
    {
        return 'Reporte de Stock Actual';
    }

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Reportes';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('total_entradas')
                    ->label('Total Entradas')
                    ->getStateUsing(function ($record) {
                        return $record->inventoryEntries()
                            ->where('entry_type', 'entrada')
                            ->selectRaw('SUM(COALESCE(quantity_base, quantity)) as total')
                            ->value('total') ?? 0;
                    })
                    ->numeric(decimalPlaces: 2)
                    ->color('success'),
                TextColumn::make('total_salidas')
                    ->label('Total Salidas')
                    ->getStateUsing(function ($record) {
                        return $record->inventoryEntries()
                            ->where('entry_type', 'salida')
                            ->selectRaw('SUM(COALESCE(quantity_base, quantity)) as total')
                            ->value('total') ?? 0;
                    })
                    ->numeric(decimalPlaces: 2)
                    ->color('danger'),
                TextColumn::make('total_ajustes')
                    ->label('Ajustes')
                    ->getStateUsing(function ($record) {
                        return $record->inventoryEntries()
                            ->where('entry_type', 'ajuste')
                            ->selectRaw('SUM(COALESCE(quantity_base, quantity)) as total')
                            ->value('total') ?? 0;
                    })
                    ->numeric(decimalPlaces: 2)
                    ->color('warning'),
                TextColumn::make('stock_actual')
                    ->label('Stock Actual')
                    ->getStateUsing(fn($record) => $record->getCurrentStock())
                    ->numeric(decimalPlaces: 2)
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray'))
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
                SelectFilter::make('is_active')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
