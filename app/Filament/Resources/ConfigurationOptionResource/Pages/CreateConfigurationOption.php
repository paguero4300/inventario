<?php

namespace App\Filament\Resources\ConfigurationOptionResource\Pages;

use App\Filament\Resources\ConfigurationOptionResource;
use App\Models\ConfigurationOption;
use Filament\Resources\Pages\CreateRecord;

class CreateConfigurationOption extends CreateRecord
{
    protected static string $resource = ConfigurationOptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si viene con parent en query string, pre-llenar
        if ($parentId = request()->query('parent')) {
            $data['parent_id'] = $parentId;

            // Heredar categoría del padre
            $parent = ConfigurationOption::find($parentId);
            if ($parent && $parent->category_id) {
                $data['category_id'] = $parent->category_id;
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
