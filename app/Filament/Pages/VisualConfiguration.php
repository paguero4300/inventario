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

    protected string $view = 'filament.pages.visual-configuration';

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

