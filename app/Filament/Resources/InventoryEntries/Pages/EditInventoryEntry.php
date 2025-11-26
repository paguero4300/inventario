<?php

namespace App\Filament\Resources\InventoryEntries\Pages;

use App\Filament\Resources\InventoryEntries\InventoryEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInventoryEntry extends EditRecord
{
    protected static string $resource = InventoryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
