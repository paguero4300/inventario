<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'default_unit_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function defaultUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function unitConversions(): HasMany
    {
        return $this->hasMany(UnitConversion::class);
    }

    public function configurationOptions(): HasMany
    {
        return $this->hasMany(ConfigurationOption::class);
    }
}
