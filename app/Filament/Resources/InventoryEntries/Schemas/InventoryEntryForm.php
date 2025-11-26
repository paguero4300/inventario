<?php

namespace App\Filament\Resources\InventoryEntries\Schemas;

use App\Models\Product;
use App\Services\UnitConversionService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InventoryEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Transaction Details')
                    ->description('Product and location information')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-m-cube')
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updateBaseQuantity($get, $set)),
                        Select::make('location_id')
                            ->relationship('location', 'code')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-map-pin'),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(fn() => auth()->id())
                            ->required()
                            ->prefixIcon('heroicon-m-user'),
                        DateTimePicker::make('entry_date')
                            ->required()
                            ->default(now())
                            ->prefixIcon('heroicon-m-calendar'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Movement Data')
                    ->description('Quantities and units')
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema([
                        Select::make('entry_type')
                            ->options([
                                'entrada' => 'Entrada',
                                'salida' => 'Salida',
                                'ajuste' => 'Ajuste',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-m-arrow-path'),
                        Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-m-scale')
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updateBaseQuantity($get, $set)),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->live(debounce: 500)
                            ->prefixIcon('heroicon-m-calculator')
                            ->afterStateUpdated(fn(Get $get, Set $set) => self::updateBaseQuantity($get, $set)),
                        TextInput::make('quantity_base')
                            ->numeric()
                            ->readOnly()
                            ->prefixIcon('heroicon-m-equals')
                            ->helperText('Calculated automatically based on unit conversion'),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function updateBaseQuantity(Get $get, Set $set): void
    {
        $quantity = (float) $get('quantity');
        $unitId = (int) $get('unit_id');
        $productId = (int) $get('product_id');

        if (! $quantity || ! $unitId || ! $productId) {
            $set('quantity_base', null);
            return;
        }

        $product = Product::find($productId);
        if (! $product) {
            return;
        }

        $service = new UnitConversionService();
        $baseQuantity = $service->convertToBase($quantity, $unitId, $product->category_id);

        $set('quantity_base', $baseQuantity);
    }
}
