<?php

namespace App\Filament\Pages;

use App\Models\ConfigurationOption;
use Filament\Pages\Page;
use Openplain\FilamentTreeView\Concerns\InteractsWithTree;
use Openplain\FilamentTreeView\Contracts\HasTree;
use Openplain\FilamentTreeView\Fields\TextField;
use Openplain\FilamentTreeView\Tree;

class VisualConfiguration extends Page implements HasTree
{
    use InteractsWithTree;

    protected string $view = 'filament-tree-view::pages.tree-page';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-rectangle-stack';
    }

    public static function getNavigationLabel(): string
    {
        return 'Configurador Visuals';
    }

    public function getTitle(): string
    {
        return 'Árbol de Configuración';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Configurador';
    }

    public static function getModel(): string
    {
        return ConfigurationOption::class;
    }

    public function tree(Tree $tree): Tree
    {
        return $tree
            ->query(ConfigurationOption::query())
            ->maxDepth(10)
            ->modifyQueryUsing(fn($query) => $query->orderBy('sort_order'))
            ->fields([
                TextField::make('name'),
                TextField::make('sku_part'),
            ]);
    }

    /**
     * Override to use 'sort_order' instead of 'order'
     */
    public function getTreeRecords(): array
    {
        $query = $this->getTree()->getQuery();

        // Get all records ordered by our custom column
        $nodes = (clone $query)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Build nested tree structure
        return $this->buildNestedArray($nodes);
    }

    /**
     * Build nested array structure from flat collection
     */
    protected function buildNestedArray($nodes, $parentId = null): array
    {
        $branch = [];
        $rootValue = null; // ConfigurationOption uses null for root nodes
        $parentKeyName = 'parent_id';

        foreach ($nodes as $node) {
            // Check if this node belongs to the requested parent level
            $isMatch = $parentId === null
                ? $node->{$parentKeyName} === $rootValue
                : $node->{$parentKeyName} == $parentId;

            if ($isMatch) {
                $children = $this->buildNestedArray($nodes, $node->id);
                // Keep the model instance and add children as a relation
                $node->setRelation('children', collect($children));
                $branch[] = $node;
            }
        }

        return $branch;
    }
}
