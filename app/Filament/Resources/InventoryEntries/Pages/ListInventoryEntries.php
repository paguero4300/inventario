<?php

namespace App\Filament\Resources\InventoryEntries\Pages;

use App\Filament\Resources\InventoryEntries\InventoryEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryEntries extends ListRecords
{
    protected static string $resource = InventoryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
