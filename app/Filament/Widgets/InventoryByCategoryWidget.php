<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class InventoryByCategoryWidget extends ChartWidget
{
    protected ?string $heading = 'Products by Category';

    protected static ?int $sortOrder = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Category::withCount('products')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => $data->pluck('products_count'),
                ],
            ],
            'labels' => $data->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
