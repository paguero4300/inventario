<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Openplain\FilamentTreeView\Concerns\HasTreeStructure;

class ConfigurationOption extends Model
{
    use HasFactory;
    use HasTreeStructure;

    protected $fillable = [
        'parent_id',
        'category_id',
        'name',
        'next_step_label',
        'sku_part',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Categoría asociada
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Obtiene el nivel de profundidad en el árbol (0 = raíz)
     */
    public function getLevel(): int
    {
        $level = 0;
        $current = $this;

        while ($current->parent_id) {
            $level++;
            $current = $current->parent;
        }

        return $level;
    }

    /**
     * Obtiene el path completo (Madera > Poste > Cedro)
     */
    public function getFullPath(string $separator = ' > '): string
    {
        $path = [$this->name];
        $current = $this;

        while ($current->parent_id) {
            $current = $current->parent;
            array_unshift($path, $current->name);
        }

        return implode($separator, $path);
    }

    /**
     * Scope: Solo opciones raíz
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Solo opciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
