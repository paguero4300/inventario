<?php

namespace App\Filament\Resources\InventoryEntries;

use App\Filament\Resources\InventoryEntries\Pages\CreateInventoryEntry;
use App\Filament\Resources\InventoryEntries\Pages\EditInventoryEntry;
use App\Filament\Resources\InventoryEntries\Pages\ListInventoryEntries;
use App\Filament\Resources\InventoryEntries\Pages\ViewInventoryEntry;
use App\Filament\Resources\InventoryEntries\Schemas\InventoryEntryForm;
use App\Filament\Resources\InventoryEntries\Schemas\InventoryEntryInfolist;
use App\Filament\Resources\InventoryEntries\Tables\InventoryEntriesTable;
use App\Models\InventoryEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryEntryResource extends Resource
{
    protected static ?string $model = InventoryEntry::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Inventario';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Schema $schema): Schema
    {
        return InventoryEntryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InventoryEntryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryEntriesTable::configure($table);
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
            'index' => ListInventoryEntries::route('/'),
            'create' => CreateInventoryEntry::route('/create'),
            'view' => ViewInventoryEntry::route('/{record}'),
            'edit' => EditInventoryEntry::route('/{record}/edit'),
        ];
    }
}
