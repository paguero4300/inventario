<x-filament-panels::page>
    {{-- TABS DE CATEGORÍAS --}}
    <x-filament::tabs label="Categorías">
        @foreach($this->categories as $category)
        <x-filament::tabs.item
            :active="$activeTab === $category->slug"
            wire:click="setActiveTab('{{ $category->slug }}')">
            {{ $category->name }}
        </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    {{-- CONTENIDO DEL ÁRBOL --}}
    <div class="mt-4 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                Jerarquía de {{ $this->categories->where('slug', $activeTab)->first()?->name }}
            </h2>

            {{ ($this->createRootAction) }}
        </div>

        @if($this->tree->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <div class="flex justify-center mb-4">
                <x-heroicon-o-folder-open class="text-gray-400" style="width: 64px; height: 64px; opacity: 0.5;" />
            </div>
            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">No hay opciones configuradas</p>
            <p class="text-sm mt-1 text-gray-500 dark:text-gray-400">Usa el botón "Nuevo Material Raíz" para comenzar.</p>
        </div>
        @else
        <ul class="space-y-2">
            @foreach($this->tree as $node)
            @include('filament.pages.partials.tree-node', ['node' => $node, 'level' => 0])
            @endforeach
        </ul>
        @endif
    </div>
</x-filament-panels::page>