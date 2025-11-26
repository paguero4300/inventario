<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryEntry extends Model
{
    protected $fillable = [
        'product_id',
        'location_id',
        'unit_id',
        'user_id',
        'quantity',
        'quantity_base',
        'entry_type',
        'notes',
        'entry_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'quantity_base' => 'decimal:2',
        'entry_date' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
