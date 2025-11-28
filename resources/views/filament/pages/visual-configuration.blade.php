<x-filament-panels::page>
    {{-- TABS DE CATEGORÍAS --}}
    <div class="flex space-x-2 overflow-x-auto border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
        @foreach($this->categories as $category)
        <button
            wire:click="setActiveTab('{{ $category->slug }}')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                {{ $activeTab === $category->slug 
                    ? 'bg-primary-600 text-white shadow-sm' 
                    : 'bg-white text-gray-600 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' 
                }}">
            {{ $category->name }}
        </button>
        @endforeach
    </div>

    {{-- CONTENIDO DEL ÁRBOL --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                Jerarquía de {{ $this->categories->where('slug', $activeTab)->first()?->name }}
            </h2>

            {{ ($this->createRootAction) }}
        </div>

        @if($this->tree->isEmpty())
        <div class="text-center py-12 text-gray-500">
            <x-heroicon-o-folder-open class="w-12 h-12 mx-auto mb-3 opacity-50" />
            <p>No hay opciones configuradas para esta categoría.</p>
            <p class="text-sm mt-2">Usa el botón "Nuevo Material Raíz" para comenzar.</p>
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