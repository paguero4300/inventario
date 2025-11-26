<?php

namespace App\Filament\Resources\UnitConversions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UnitConversionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->numeric(),
                TextEntry::make('fromUnit.name')
                    ->numeric(),
                TextEntry::make('toUnit.name')
                    ->numeric(),
                TextEntry::make('conversion_factor')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
