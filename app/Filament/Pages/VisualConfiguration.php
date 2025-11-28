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
            ->maxDepth(10)
            ->modifyQueryUsing(fn($query) => $query->orderBy('sort_order'))
            ->fields([
                TextField::make('name')
                    ->label('Nombre'),
                TextField::make('sku_part')
                    ->label('SKU'),
            ]);
    }
}
