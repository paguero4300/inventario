<?php

namespace App\Filament\Pages;

use App\Models\ConfigurationOption;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
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
            ->query(ConfigurationOption::query())
            ->maxDepth(10)
            ->modifyQueryUsing(fn($query) => $query->orderBy('sort_order'))
            ->fields([
                TextField::make('name'),
                TextField::make('sku_part'),
            ])
            ->recordActions([
                Action::make('addChild')
                    ->label('Añadir Hijo')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('sku_part')
                            ->label('SKU')
                            ->maxLength(50),
                        Select::make('parent_id')
                            ->label('Padre')
                            ->options(ConfigurationOption::query()->pluck('name', 'id'))
                            ->searchable()
                            ->disabled()
                            ->dehydrated(),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->options(function () {
                                return \App\Models\Category::query()
                                    ->where('is_active', true)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->nullable(),
                        TextInput::make('next_step_label')
                            ->label('Etiqueta Siguiente Paso')
                            ->maxLength(100),
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->fillForm(fn(ConfigurationOption $record): array => [
                        'parent_id' => $record->id,
                    ])
                    ->action(function (ConfigurationOption $record, array $data) {
                        ConfigurationOption::create($data);

                        Notification::make()
                            ->title('Hijo creado exitosamente')
                            ->success()
                            ->send();
                    }),
                Action::make('edit')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->fillForm(fn(ConfigurationOption $record): array => [
                        'name' => $record->name,
                        'sku_part' => $record->sku_part,
                        'parent_id' => $record->parent_id,
                        'category_id' => $record->category_id,
                        'next_step_label' => $record->next_step_label,
                        'sort_order' => $record->sort_order,
                        'is_active' => $record->is_active,
                    ])
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('sku_part')
                            ->label('SKU')
                            ->maxLength(50),
                        Select::make('parent_id')
                            ->label('Padre')
                            ->options(
                                fn(ConfigurationOption $record) =>
                                ConfigurationOption::query()
                                    ->where('id', '!=', $record->id)
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->nullable(),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->options(function () {
                                return \App\Models\Category::query()
                                    ->where('is_active', true)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->nullable(),
                        TextInput::make('next_step_label')
                            ->label('Etiqueta Siguiente Paso')
                            ->maxLength(100),
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->action(function (ConfigurationOption $record, array $data) {
                        $record->update($data);

                        Notification::make()
                            ->title('Opción actualizada exitosamente')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make()
                    ->label('Eliminar')
                    ->modalDescription(function (ConfigurationOption $record): string {
                        $count = $record->getDescendantsCount();
                        if ($count === 0) {
                            return '¿Estás seguro de que deseas eliminar esta opción?';
                        }
                        return "Esta opción tiene {$count} descendientes que también serán eliminados.";
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Nueva Opción')
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('sku_part')
                        ->label('SKU')
                        ->maxLength(50),
                    Select::make('parent_id')
                        ->label('Padre')
                        ->options(ConfigurationOption::query()->pluck('name', 'id'))
                        ->searchable()
                        ->nullable(),
                    Select::make('category_id')
                        ->label('Categoría')
                        ->options(function () {
                            return \App\Models\Category::query()
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->nullable(),
                    TextInput::make('next_step_label')
                        ->label('Etiqueta Siguiente Paso')
                        ->maxLength(100),
                    TextInput::make('sort_order')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->label('Activo')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    ConfigurationOption::create($data);

                    Notification::make()
                        ->title('Opción creada exitosamente')
                        ->success()
                        ->send();
                }),
        ];
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
