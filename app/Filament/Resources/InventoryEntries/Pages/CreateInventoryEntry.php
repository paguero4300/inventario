<?php

namespace App\Filament\Resources\InventoryEntries\Pages;

use App\Filament\Resources\InventoryEntries\InventoryEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryEntry extends CreateRecord
{
    protected static string $resource = InventoryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('clear')
                ->label('Limpiar Formulario')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->action(fn() => $this->form->fill()),
        ];
    }
}
