<?php

namespace App\Filament\Pages;

use App\Models\ConfigurationOption;
use Filament\Pages\Page;
use OpenPlain\FilamentTreeView\Concerns\InteractsWithTree;
use OpenPlain\FilamentTreeView\Fields\TextField;
use OpenPlain\FilamentTreeView\Tree\Tree;

class VisualConfiguration extends Page
{
    use InteractsWithTree;

    protected static string $view = 'filament-tree-view::pages.tree-page';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-rectangle-stack';
    }

    public static function getNavigationLabel(): string
    {
        return 'Configurador Visual';
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
            ->model(ConfigurationOption::class)
            ->maxDepth(10)
            ->enableDragAndDrop()
            ->tree(fn($query) => $query->orderBy('sort_order'))
            ->schema([
                TextField::make('name')
                    ->label('Nombre'),
                TextField::make('sku_part')
                    ->label('SKU'),
            ]);
    }
}
