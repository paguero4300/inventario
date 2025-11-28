<?php

namespace App\Livewire;

use App\Models\ConfigurationOption;
use App\Models\Category;
use Livewire\Component;

class ProductConfiguratorSelect extends Component
{
    // Props que vienen del formulario de Filament
    public $categoryId;
    public $onSelectionChange;

    // Estado interno
    public $selects = [];
    public $selections = [];
    public $generatedSku = '';
    public $configuration = [];

    public function mount($categoryId = null)
    {
        $this->categoryId = $categoryId;

        if ($categoryId) {
            $this->loadRootOptions();
        }
    }

    public function updatedCategoryId($value)
    {
        $this->resetConfigurator();
        if ($value) {
            $this->loadRootOptions();
        }
    }

    public function loadRootOptions()
    {
        $options = ConfigurationOption::roots()
            ->active()
            ->where('category_id', $this->categoryId)
            ->orderBy('sort_order')
            ->get();

        if ($options->isNotEmpty()) {
            $this->selects = [[
                'level' => 0,
                'label' => 'Seleccione Material',
                'options' => $options->toArray(),
                'selected' => null,
            ]];
        }
    }

    public function selectOption($level, $optionId)
    {
        // Paso CRÍTICO: Reset en cascada
        $this->resetSelectsAfter($level);

        // Guardar selección
        $this->selections[$level] = $optionId;

        // Obtener la opción seleccionada
        $selectedOption = ConfigurationOption::find($optionId);

        if (!$selectedOption) {
            return;
        }

        // Guardar en configuración
        $this->configuration[$level] = [
            'id' => $selectedOption->id,
            'name' => $selectedOption->name,
            'sku_part' => $selectedOption->sku_part,
        ];

        // Buscar hijos
        $children = $selectedOption->children()->get();

        if ($children->isNotEmpty()) {
            // Agregar nuevo select3
            $this->selects[] = [
                'level' => $level + 1,
                'label' => $selectedOption->next_step_label ?? 'Siguiente Paso',
                'options' => $children->toArray(),
                'selected' => null,
            ];
        } else {
            // Es hoja final - generar SKU
            $this->generateSku();
        }

        // Emitir evento para actualizar el formulario padre
        $this->dispatch('configurationUpdated', [
            'sku' => $this->generatedSku,
            'configuration' => $this->configuration,
        ]);
    }

    private function resetSelectsAfter($level)
    {
        $this->selects = array_slice($this->selects, 0, $level + 1);
        $this->selections = array_slice($this->selections, 0, $level + 1, true);
        $this->configuration = array_slice($this->configuration, 0, $level + 1, true);
        $this->generatedSku = '';
    }

    private function resetConfigurator()
    {
        $this->selects = [];
        $this->selections = [];
        $this->configuration = [];
        $this->generatedSku = '';
    }

    private function generateSku()
    {
        $skuParts = [];

        foreach ($this->configuration as $config) {
            if (!empty($config['sku_part'])) {
                $skuParts[] = $config['sku_part'];
            }
        }

        $this->generatedSku = implode('-', $skuParts);
    }

    public function render()
    {
        return view('livewire.product-configurator-select');
    }
}
