<?php

namespace App\Filament\Pages;

use App\Models\ConfigurationOption;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
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

    public function getHeading(): string
    {
        return '🌳 Configurador Visual de Productos';
    }

    public function getSubheading(): ?string
    {
        return 'Gestiona el árbol de opciones de configuración. Máximo 10 niveles (0-9). El orden se asigna automáticamente.';
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
                    ->disabled(function (ConfigurationOption $record) {
                        // Disable if already at max depth (level 9)
                        return $record->getLevel() >= 9;
                    })
                    ->tooltip(function (ConfigurationOption $record) {
                        $currentLevel = $record->getLevel();
                        if ($currentLevel >= 9) {
                            return '⚠️ Máximo nivel alcanzado (10 niveles, 0-9)';
                        }
                        return "Nivel actual: {$currentLevel} | Máx: 9";
                    })
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(100)
                            ->helperText(function (Get $get, $state, $record) {
                                $parent = ConfigurationOption::find($get('parent_id'));
                                if ($parent) {
                                    $currentLevel = $parent->getLevel();
                                    $newLevel = $currentLevel + 1;
                                    return "📊 Este elemento estará en el nivel {$newLevel} (de 0-9)";
                                }
                                return null;
                            }),
                        TextInput::make('sku_part')
                            ->label('Parte del SKU')
                            ->maxLength(50)
                            ->helperText('Esta parte se concatenará para formar el SKU final del producto'),
                        Hidden::make('parent_id')
                            ->dehydrated(),
                        Placeholder::make('parent_info')
                            ->label('Padre')
                            ->content(function (Get $get) {
                                $parentId = $get('parent_id');
                                if ($parentId) {
                                    $parent = ConfigurationOption::find($parentId);
                                    return $parent ? "{$parent->name} (Nivel: {$parent->getLevel()})" : "ID: {$parentId}";
                                }
                                return 'No asignado';
                            })
                            ->helperText('Este campo se asigna automáticamente'),
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
                            ->nullable()
                            ->helperText('Solo requerido para elementos raíz (nivel 0)'),
                        TextInput::make('next_step_label')
                            ->label('Etiqueta Siguiente Paso')
                            ->maxLength(100)
                            ->placeholder('Ej: Seleccione Altura, Seleccione Color, etc.')
                            ->helperText('Esta etiqueta aparecerá en el formulario de productos para el siguiente nivel'),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Solo las opciones activas aparecerán en el formulario de productos'),
                    ])
                    ->fillForm(fn(ConfigurationOption $record): array => [
                        'parent_id' => $record->id,
                        'category_id' => $record->category_id, // Inherit category
                    ])
                    ->action(function (ConfigurationOption $record, array $data) {
                        // Check depth before creating
                        if ($record->getLevel() >= 9) {
                            Notification::make()
                                ->title('⚠️ Nivel máximo alcanzado')
                                ->body('No se pueden crear más niveles. El máximo es 10 niveles (0-9).')
                                ->warning()
                                ->send();
                            return;
                        }

                        // Auto-calculate sort_order based on siblings
                        $maxOrder = ConfigurationOption::where('parent_id', $record->id)
                            ->max('sort_order') ?? -1;
                        $data['sort_order'] = $maxOrder + 1;

                        $newChild = ConfigurationOption::create($data);

                        Notification::make()
                            ->title('✅ Hijo creado exitosamente')
                            ->body("'{$newChild->name}' fue creado en el nivel " . $newChild->getLevel())
                            ->success()
                            ->duration(5000)
                            ->send();
                    })
                    ->successNotificationTitle('Elemento creado')
                    ->after(function () {
                        // Refresh the tree to show the new item
                        $this->dispatch('refreshTree');
                    }),
                Action::make('edit')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->tooltip(function (ConfigurationOption $record) {
                        return "Nivel: {$record->getLevel()}" . ($record->category ? " | Categoría: {$record->category->name}" : '');
                    })
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
                            ->label('Orden (Auto)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('El orden se calcula automáticamente'),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->action(function (ConfigurationOption $record, array $data) {
                        // Keep existing sort_order on edit
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
                    Toggle::make('is_active')
                        ->label('Activo')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    // Auto-calculate sort_order for root level items
                    if (isset($data['parent_id'])) {
                        $maxOrder = ConfigurationOption::where('parent_id', $data['parent_id'])
                            ->max('sort_order') ?? -1;
                    } else {
                        // For root items, calculate based on category
                        $maxOrder = ConfigurationOption::whereNull('parent_id')
                            ->where('category_id', $data['category_id'] ?? null)
                            ->max('sort_order') ?? -1;
                    }
                    $data['sort_order'] = $maxOrder + 1;

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
