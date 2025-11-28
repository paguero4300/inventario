<?php

namespace App\Filament\Resources\ConfigurationOptionResource\Pages;

use App\Filament\Resources\ConfigurationOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConfigurationOption extends EditRecord
{
    protected static string $resource = ConfigurationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
