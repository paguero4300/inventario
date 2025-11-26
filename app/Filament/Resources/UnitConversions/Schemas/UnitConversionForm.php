<?php

namespace App\Filament\Resources\UnitConversions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitConversionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Conversion Details')
                    ->description('Define conversion factors between units')
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->prefixIcon('heroicon-m-tag'),
                        TextInput::make('conversion_factor')
                            ->required()
                            ->numeric()
                            ->prefixIcon('heroicon-m-calculator'),
                        Select::make('from_unit_id')
                            ->relationship('fromUnit', 'name')
                            ->required()
                            ->prefixIcon('heroicon-m-arrow-left'),
                        Select::make('to_unit_id')
                            ->relationship('toUnit', 'name')
                            ->required()
                            ->prefixIcon('heroicon-m-arrow-right'),
                    ])->columns(2),
            ]);
    }
}
