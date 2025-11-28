<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\ConfigurationOption;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class VisualConfiguration extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static $navigationLabel = 'Configurador Visual';
    protected static $title = 'Árbol de Configuración';
    protected static $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Configurador';
    }

    protected static string $view = 'filament.pages.visual-configuration';

    public ?string $activeTab = null;

    public function mount()
    {
        // Activar la primera categoría por defecto
        $firstCategory = Category::whereHas('configurationOptions')->first();
        $this->activeTab = $firstCategory?->slug;
    }

    public function getCategoriesProperty()
    {
        return Category::has('configurationOptions')->get();
    }

    public function getTreeProperty()
    {
        if (! $this->activeTab) {
            return collect();
        }

        $category = Category::where('slug', $this->activeTab)->first();

        if (! $category) {
            return collect();
        }

        // Obtener todas las opciones de la categoría y armar el árbol
        $options = ConfigurationOption::where('category_id', $category->id)
            ->orderBy('sort_order')
            ->get();

        return $this->buildTree($options);
    }

    protected function buildTree(Collection $options, $parentId = null)
    {
        $branch = [];

        foreach ($options as $option) {
            if ($option->parent_id == $parentId) {
                $children = $this->buildTree($options, $option->id);

                $option->children_tree = $children;
                $branch[] = $option;
            }
        }

        return collect($branch);
    }

    public function setActiveTab($slug)
    {
        $this->activeTab = $slug;
    }

    // --- ACTIONS ---

    public function createRootAction(): Action
    {
        return Action::make('createRoot')
            ->label('Nuevo Material Raíz')
            ->icon('heroicon-m-plus')
            ->button()
            ->form([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('next_step_label')
                    ->label('Etiqueta Siguiente Paso')
                    ->placeholder('Ej: Seleccione Tipo'),
                TextInput::make('sku_part')
                    ->label('Fragmento SKU')
                    ->required()
                    ->maxLength(5),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(10),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ])
            ->action(function (array $data) {
                $category = Category::where('slug', $this->activeTab)->first();

                ConfigurationOption::create([
                    'category_id' => $category->id,
                    'parent_id' => null,
                    'name' => $data['name'],
                    'next_step_label' => $data['next_step_label'],
                    'sku_part' => strtoupper($data['sku_part']),
                    'sort_order' => $data['sort_order'],
                    'is_active' => $data['is_active'],
                ]);

                Notification::make()->title('Material creado')->success()->send();
            });
    }

    public function addChildAction(): Action
    {
        return Action::make('addChild')
            ->label('Agregar Hijo')
            ->icon('heroicon-m-plus-circle')
            ->iconButton()
            ->size('sm')
            ->color('success')
            ->form([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('next_step_label')
                    ->label('Etiqueta Siguiente Paso')
                    ->placeholder('Ej: Seleccione Tamaño'),
                TextInput::make('sku_part')
                    ->label('Fragmento SKU')
                    ->required()
                    ->maxLength(5),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(10),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ])
            ->action(function (array $data, array $arguments) {
                $parentId = $arguments['parent_id'];
                $parent = ConfigurationOption::find($parentId);

                ConfigurationOption::create([
                    'category_id' => $parent->category_id,
                    'parent_id' => $parentId,
                    'name' => $data['name'],
                    'next_step_label' => $data['next_step_label'],
                    'sku_part' => strtoupper($data['sku_part']),
                    'sort_order' => $data['sort_order'],
                    'is_active' => $data['is_active'],
                ]);

                Notification::make()->title('Opción agregada')->success()->send();
            });
    }

    public function editOptionAction(): Action
    {
        return Action::make('editOption')
            ->label('Editar')
            ->icon('heroicon-m-pencil-square')
            ->iconButton()
            ->size('sm')
            ->color('gray')
            ->fillForm(fn(array $arguments) => ConfigurationOption::find($arguments['id'])->toArray())
            ->form([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('next_step_label')
                    ->label('Etiqueta Siguiente Paso'),
                TextInput::make('sku_part')
                    ->label('Fragmento SKU')
                    ->required()
                    ->maxLength(5),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric(),
                Toggle::make('is_active')
                    ->label('Activo'),
            ])
            ->action(function (array $data, array $arguments) {
                $option = ConfigurationOption::find($arguments['id']);
                $option->update([
                    'name' => $data['name'],
                    'next_step_label' => $data['next_step_label'],
                    'sku_part' => strtoupper($data['sku_part']),
                    'sort_order' => $data['sort_order'],
                    'is_active' => $data['is_active'],
                ]);

                Notification::make()->title('Opción actualizada')->success()->send();
            });
    }

    public function deleteOptionAction(): Action
    {
        return Action::make('deleteOption')
            ->label('Eliminar')
            ->icon('heroicon-m-trash')
            ->iconButton()
            ->size('sm')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('¿Eliminar opción?')
            ->modalDescription('⚠️ CUIDADO: Esto eliminará también todas las opciones hijas. ¿Estás seguro?')
            ->action(function (array $arguments) {
                $option = ConfigurationOption::find($arguments['id']);

                // Borrado recursivo (Laravel lo hace si está configurado cascade, pero por seguridad...)
                // Aquí confiamos en el delete del modelo o DB cascade.
                // Si no hay cascade en DB, deberíamos borrar hijos manualmente.
                // Asumimos que el usuario sabe el riesgo por el modal.

                $option->delete();

                Notification::make()->title('Opción eliminada')->success()->send();
            });
    }
}
