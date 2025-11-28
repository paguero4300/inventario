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

    /**
     * Boot method to override deletion behavior
     */
    protected static function boot()
    {
        parent::boot();

        // CRITICAL: Override the trait's recursive delete behavior
        // The HasTreeStructure trait uses CTEs which break on MariaDB
        static::deleting(function (ConfigurationOption $option) {
            // Prevent infinite loop by checking if we're already deleting children
            if (!isset($option->deletingChildren)) {
                $option->deletingChildren = true;
                $option->deleteDescendantsManually();
            }
        });
    }

    /**
     * Override the trait's boot method to prevent CTE-based delete registration
     * This completely disables the trait's automatic delete behavior
     */
    protected static function bootHasTreeStructure()
    {
        // DO NOTHING - this prevents the trait from registering its own delete events
        // which use CTEs and break on MariaDB
    }


    /**
     * Manually delete all descendants (workaround for MariaDB CTE issue)
     */
    protected function deleteDescendantsManually(): void
    {
        $children = static::where('parent_id', $this->id)->get();

        foreach ($children as $child) {
            // Recursively delete children first
            $child->delete();
        }
    }

    /**
     * Get descendants count (manual implementation)
     */
    public function getDescendantsCount(): int
    {
        $count = 0;
        $children = static::where('parent_id', $this->id)->get();

        foreach ($children as $child) {
            $count++; // Count this child
            $count += $child->getDescendantsCount(); // Add its descendants
        }

        return $count;
    }

    /**
     * Override the trait's descendants() method to avoid CTE issues
     * Returns a collection instead of a query builder to prevent CTE usage
     */
    public function descendants()
    {
        return $this->getDescendantsCollection();
    }

    /**
     * Manually get all descendants as a collection (workaround for MariaDB CTE issue)
     */
    protected function getDescendantsCollection()
    {
        $descendants = collect();
        $children = static::where('parent_id', $this->id)->get();

        foreach ($children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getDescendantsCollection());
        }

        return $descendants;
    }
}
