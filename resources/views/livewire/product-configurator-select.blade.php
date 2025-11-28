<div class="space-y-4">
    @foreach ($selects as $index => $select)
    <div class="fi-fo-field-wrp">
        <div class="grid gap-2">
            <div class="flex items-center gap-2">
                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        {{ $select['label'] }}
                    </span>
                </label>
            </div>

            <div class="grid gap-y-2">
                <div class="fi-input-wrp">
                    <select
                        wire:change="selectOption({{ $index }}, $event.target.value)"
                        class="fi-input block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Seleccione --</option>
                        @foreach ($select['options'] as $option)
                        <option
                            value="{{ $option['id'] }}"
                            @if(isset($selections[$index]) && $selections[$index]==$option['id']) selected @endif>
                            {{ $option['name'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if ($generatedSku)
    <div class="fi-fo-field-wrp">
        <div class="rounded-lg bg-success-50 dark:bg-success-500/10 p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-success-600 dark:text-success-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-success-700 dark:text-success-300">
                        Configuración Completa
                    </p>
                    <p class="text-xs text-success-600 dark:text-success-400 mt-1">
                        SKU Generado: <code class="font-mono font-bold">{{ $generatedSku }}</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>