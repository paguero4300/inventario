<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold">Reporte de Stock Actual</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Visualiza el stock actual de todos los productos basado en entradas, salidas y ajustes del inventario.
                    </p>
                </div>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>