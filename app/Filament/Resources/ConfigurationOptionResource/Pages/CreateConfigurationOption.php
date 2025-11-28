<?php

namespace App\Filament\Resources\ConfigurationOptionResource\Pages;

use App\Filament\Resources\ConfigurationOptionResource;
use App\Models\ConfigurationOption;
use Filament\Resources\Pages\CreateRecord;

class CreateConfigurationOption extends CreateRecord
{
    protected static string $resource = ConfigurationOptionResource::class;

    public function mount(): void
    {
        parent::mount();

        // Pre-llenar formulario si viene con parent en URL
        if ($parentId = request()->query('parent')) {
            $parent = ConfigurationOption::find($parentId);

            if ($parent) {
                $this->form->fill([
                    'parent_id' => $parentId,
                    'category_id' => $parent->category_id,
                ]);
            }
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Si viene con parent en query string, asegurar que se guarde
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
