@props(['node', 'level'])

<li class="relative" style="margin-left: {{ $level * 20 }}px">
    {{-- Línea conectora vertical (si no es raíz) --}}
    @if($level > 0)
    <div class="absolute -left-3 top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700 h-full"></div>
    <div class="absolute -left-3 top-3.5 w-3 h-px bg-gray-200 dark:bg-gray-700"></div>
    @endif

    <div class="flex items-center group">
        {{-- Tarjeta del Nodo --}}
        <div class="flex-1 flex items-center justify-between p-3 rounded-lg border 
            {{ $node->is_active ? 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700' : 'bg-red-50 border-red-200 opacity-75' }}
            hover:border-primary-400 transition-all duration-200">

            <div class="flex items-center gap-3">
                {{-- Icono según si tiene hijos --}}
                @if($node->children_tree->isNotEmpty())
                <x-heroicon-m-folder class="w-5 h-5 text-yellow-500" />
                @else
                <x-heroicon-m-document class="w-5 h-5 text-gray-400" />
                @endif

                <div>
                    <div class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $node->name }}
                        <span class="text-xs px-1.5 py-0.5 rounded bg-gray-200 text-gray-600 font-mono">
                            {{ $node->sku_part }}
                        </span>
                    </div>
                    @if($node->next_step_label)
                    <div class="text-xs text-gray-500">
                        Siguiente: {{ $node->next_step_label }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Acciones (visibles al hover o siempre en móvil) --}}
            <div class="flex items-center gap-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                {{ ($this->addChildAction)(['parent_id' => $node->id]) }}
                {{ ($this->editOptionAction)(['id' => $node->id]) }}
                {{ ($this->deleteOptionAction)(['id' => $node->id]) }}
            </div>
        </div>
    </div>

    {{-- Recursividad para hijos --}}
    @if($node->children_tree->isNotEmpty())
    <ul class="mt-2 space-y-2 relative">
        {{-- Línea vertical continua para los hijos --}}
        <div class="absolute left-3 top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700 -ml-[2px]"></div>

        @foreach($node->children_tree as $child)
        @include('filament.pages.partials.tree-node', ['node' => $child, 'level' => $level + 1])
        @endforeach
    </ul>
    @endif
</li>