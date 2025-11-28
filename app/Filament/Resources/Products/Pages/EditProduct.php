<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load configuration levels from specifications
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            for ($i = 0; $i <= 4; $i++) {
                $key = "config_level_{$i}";
                if (isset($data['specifications'][$key])) {
                    $data[$key] = $data['specifications'][$key];
                }
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Save configuration levels in specifications
        $specifications = [];

        for ($i = 0; $i <= 4; $i++) {
            $key = "config_level_{$i}";
            if (isset($data[$key]) && $data[$key]) {
                $specifications[$key] = $data[$key];
            }
            // Remove from main data as these are not table columns
            unset($data[$key]);
        }

        // Save specifications as JSON
        $data['specifications'] = $specifications;

        return $data;
    }
}
