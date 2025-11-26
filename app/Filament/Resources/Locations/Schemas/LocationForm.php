<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Location Details')
                    ->description('Warehouse location information')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->prefixIcon('heroicon-m-qr-code'),
                        TextInput::make('rack')
                            ->prefixIcon('heroicon-m-table-cells'),
                        TextInput::make('bin')
                            ->prefixIcon('heroicon-m-inbox'),
                        Toggle::make('is_active')
                            ->required()
                            ->inline(false),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
