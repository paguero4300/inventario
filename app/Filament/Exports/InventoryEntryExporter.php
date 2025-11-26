<?php

namespace App\Filament\Exports;

use App\Models\InventoryEntry;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class InventoryEntryExporter extends Exporter
{
    protected static ?string $model = InventoryEntry::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('product.name'),
            ExportColumn::make('location.id'),
            ExportColumn::make('unit.name'),
            ExportColumn::make('user.name'),
            ExportColumn::make('quantity'),
            ExportColumn::make('quantity_base'),
            ExportColumn::make('entry_type'),
            ExportColumn::make('notes'),
            ExportColumn::make('entry_date'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your inventory entry export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
