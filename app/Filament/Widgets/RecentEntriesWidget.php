<?php

namespace App\Filament\Widgets;

use App\Models\InventoryEntry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentEntriesWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sortOrder = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryEntry::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'entrada' => 'success',
                        'salida' => 'danger',
                        'ajuste' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(),
                Tables\Columns\TextColumn::make('unit.abbreviation')
                    ->label('Unit'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User'),
                Tables\Columns\TextColumn::make('entry_date')
                    ->dateTime(),
            ]);
    }
}
