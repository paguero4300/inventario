<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
