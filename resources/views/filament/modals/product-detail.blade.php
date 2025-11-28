<div class="p-6 space-y-6">
    {{-- Información Básica --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Información Básica</h3>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoría</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->category->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">SKU</dt>
                <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">{{ $product->sku }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</dt>
                <dd class="mt-1">
                    @if($product->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                        Activo
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                        Inactivo
                    </span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    {{-- Configuración del Producto --}}
    @if($product->specifications && !empty($product->specifications))
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Configuración del Producto</h3>
        <div class="space-y-3">
            @php
            $configNames = [
            0 => 'Material',
            1 => 'Tipo',
            2 => 'Color',
            3 => 'Altura',
            4 => 'Nivel 4'
            ];
            @endphp
            @foreach(range(0, 4) as $i)
            @php
            $key = "config_level_{$i}";
            @endphp
            @if(isset($product->specifications[$key]))
            @php
            $option = \App\Models\ConfigurationOption::find($product->specifications[$key]);
            @endphp
            @if($option)
            <div class="flex items-start">
                <span class="flex-shrink-0 w-28 text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $configNames[$i] ?? "Nivel {$i}" }}:
                </span>
                <span class="text-sm text-gray-900 dark:text-white font-semibold">
                    {{ $option->name }}
                    @if($option->sku_part)
                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400 font-mono">({{ $option->sku_part }})</span>
                    @endif
                </span>
            </div>
            @endif
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Detalles Adicionales --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detalles Adicionales</h3>
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dimensiones</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->dimensions ?? 'No especificado' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Color</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->color ?? 'No especificado' }}</dd>
            </div>
            @if($product->notes)
            <div class="col-span-2">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notas</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Información del Sistema --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">Información del Sistema</h3>
        <dl class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Creado</dt>
                <dd class="mt-1 text-gray-900 dark:text-white">{{ $product->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 dark:text-gray-400">Actualizado</dt>
                <dd class="mt-1 text-gray-900 dark:text-white">{{ $product->updated_at->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>
</div>