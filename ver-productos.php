<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRODUCTOS Y SUS CONFIGURACIONES ===\n\n";

$products = DB::table('products')
    ->select('id', 'sku', 'name', 'specifications', 'category_id')
    ->get();

if ($products->isEmpty()) {
    echo "No hay productos registrados.\n";
} else {
    foreach ($products as $product) {
        echo "ID: {$product->id}\n";
        echo "SKU: {$product->sku}\n";
        echo "Nombre: {$product->name}\n";
        echo "Categoría ID: {$product->category_id}\n";
        echo "Configuración:\n";

        if ($product->specifications) {
            $specs = json_decode($product->specifications, true);
            if ($specs && is_array($specs)) {
                foreach ($specs as $key => $value) {
                    echo "  - {$key}: {$value}\n";
                }
            } else {
                echo "  (vacío o formato inválido)\n";
            }
        } else {
            echo "  (sin configuración)\n";
        }

        echo "-----------------------------------\n\n";
    }
}

echo "Total de productos: " . $products->count() . "\n";
