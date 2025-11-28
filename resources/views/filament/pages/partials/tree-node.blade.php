@props(['node', 'level'])

<li class="relative list-none" style="margin-left: {{ $level * 24 }}px">
    {{-- Línea conectora vertical (si no es raíz) --}}
    @if($level > 0)
    <div class="absolute -left-4 top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700 h-full"></div>
    <div class="absolute -left-4 top-5 w-4 h-px bg-gray-200 dark:bg-gray-700"></div>
    @endif

    <div class="flex items-center group mb-2">
        {{-- Tarjeta del Nodo --}}
        <div class="w-full flex flex-row items-center justify-between p-3 rounded-lg border shadow-sm
            {{ $node->is_active 
                ? 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700' 
                : 'bg-gray-50 border-gray-200 opacity-60 dark:bg-gray-900 dark:border-gray-700' 
            }}
            hover:border-primary-500 dark:hover:border-primary-500 transition-all duration-200">

            {{-- Contenido Izquierdo --}}
            <div class="flex flex-row items-center gap-3 min-w-0">
                {{-- Icono --}}
                <div class="flex-shrink-0">
                    @if($node->children_tree->isNotEmpty())
                    <x-heroicon-m-folder class="text-yellow-500" style="width: 24px; height: 24px;" />
                    @else
                    <x-heroicon-m-document class="text-gray-400" style="width: 24px; height: 24px;" />
                    @endif
                </div>

                {{-- Textos --}}
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-900 dark:text-white truncate">
                            {{ $node->name }}
                        </span>
                        <span class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 font-mono border border-gray-200 dark:border-gray-600">
                            {{ $node->sku_part }}
                        </span>
                    </div>
                    @if($node->next_step_label)
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                        {{-- Línea vertical continua para los hijos --}}
                        <div class="absolute left-4 top-0 bottom-0 w-px bg-gray-200 dark:bg-gray-700 -ml-[2px]"></div>

                        @foreach($node->children_tree as $child)
                        @include('filament.pages.partials.tree-node', ['node' => $child, 'level' => $level + 1])
                        @endforeach
                        </ul>
                        @endif
</li>