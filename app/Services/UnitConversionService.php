<?php

namespace App\Services;

use App\Models\Unit;
use App\Models\UnitConversion;

class UnitConversionService
{
    public function convert(float $quantity, int $fromUnitId, int $toUnitId, ?int $categoryId = null): ?float
    {
        if ($fromUnitId === $toUnitId) {
            return $quantity;
        }

        $factor = $this->getConversionFactor($fromUnitId, $toUnitId, $categoryId);

        if ($factor) {
            return $quantity * $factor;
        }

        return null;
    }

    public function convertToBase(float $quantity, int $unitId, ?int $categoryId = null): ?float
    {
        $baseUnit = Unit::where('is_base_unit', true)->first();

        if (! $baseUnit) {
            return null;
        }

        return $this->convert($quantity, $unitId, $baseUnit->id, $categoryId);
    }

    public function getConversionFactor(int $fromUnitId, int $toUnitId, ?int $categoryId = null): ?float
    {
        // 1. Try specific category conversion
        if ($categoryId) {
            $conversion = UnitConversion::where('category_id', $categoryId)
                ->where('from_unit_id', $fromUnitId)
                ->where('to_unit_id', $toUnitId)
                ->first();

            if ($conversion) {
                return $conversion->conversion_factor;
            }
        }

        // 2. Try global conversion
        $conversion = UnitConversion::whereNull('category_id')
            ->where('from_unit_id', $fromUnitId)
            ->where('to_unit_id', $toUnitId)
            ->first();

        if ($conversion) {
            return $conversion->conversion_factor;
        }

        // 3. Try inverse conversion (1 / factor) - Optional but good practice
        // For now, we strictly follow the table structure which defines one-way or explicit conversions.
        // If we needed inverse, we would look for to->from and take 1/factor.

        return null;
    }
}
