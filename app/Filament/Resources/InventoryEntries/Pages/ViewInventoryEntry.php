<?php

namespace App\Filament\Resources\InventoryEntries\Pages;

use App\Filament\Resources\InventoryEntries\InventoryEntryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInventoryEntry extends ViewRecord
{
    protected static string $resource = InventoryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
