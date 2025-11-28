<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class ConfigurationOption extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

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
     * Relación recursiva: Padre inmediato
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ConfigurationOption::class, 'parent_id');
    }

    /**
     * Relación recursiva: Hijos directos
     */
    public function children(): HasMany
    {
        return $this->hasMany(ConfigurationOption::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Todos los descendientes (hijos, nietos, etc.)
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(ConfigurationOption::class, 'parent_id');
    }

    /**
     * Categoría asociada
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Verifica si tiene hijos
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Verifica si es nodo raíz
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
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
