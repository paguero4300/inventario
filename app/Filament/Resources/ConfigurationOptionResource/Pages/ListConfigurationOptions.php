<?php

namespace App\Filament\Resources\ConfigurationOptionResource\Pages;

use App\Filament\Resources\ConfigurationOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConfigurationOptions extends ListRecords
{
    protected static string $resource = ConfigurationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nueva Opción')
                ->icon('heroicon-m-plus'),
        ];
    }
}
