<?php

namespace App\Filament\Resources\InventoryEntries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InventoryEntryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product.name')
                    ->numeric(),
                TextEntry::make('location.id')
                    ->numeric(),
                TextEntry::make('unit.name')
                    ->numeric(),
                TextEntry::make('user.name')
                    ->numeric(),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('quantity_base')
                    ->numeric(),
                TextEntry::make('entry_type'),
                TextEntry::make('entry_date')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
