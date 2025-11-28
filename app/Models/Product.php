<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'specifications',
        'dimensions',
        'color',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventoryEntries(): HasMany
    {
        return $this->hasMany(InventoryEntry::class);
    }

    /**
     * Calculate current stock based on inventory entries
     * Entradas (+) - Salidas (-) + Ajustes
     * Uses quantity_base if available, falls back to quantity
     */
    public function getCurrentStock(?int $locationId = null): float
    {
        $query = $this->inventoryEntries();

        if ($locationId !== null) {
            $query->where('location_id', $locationId);
        }

        // Sum COALESCE per row (not COALESCE of sums)
        $entradas = $query->clone()
            ->where('entry_type', 'entrada')
            ->selectRaw('SUM(COALESCE(quantity_base, quantity)) as total')
            ->value('total') ?? 0;

        $salidas = $query->clone()
            ->where('entry_type', 'salida')
            ->selectRaw('SUM(COALESCE(quantity_base, quantity)) as total')
            ->value('total') ?? 0;

        $ajustes = $query->clone()
            ->where('entry_type', 'ajuste')
            ->selectRaw('SUM(COALESCE(quantity_base, quantity)) as total')
            ->value('total') ?? 0;

        return $entradas - $salidas + $ajustes;
    }

    /**
     * Get stock grouped by location
     */
    public function getStockByLocation(): array
    {
        $locations = $this->inventoryEntries()
            ->select('location_id')
            ->distinct()
            ->whereNotNull('location_id')
            ->pluck('location_id');

        $stockByLocation = [];

        foreach ($locations as $locationId) {
            $stockByLocation[$locationId] = $this->getCurrentStock($locationId);
        }

        return $stockByLocation;
    }
}
