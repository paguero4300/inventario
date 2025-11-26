<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $ft = Unit::where('abbreviation', 'ft')->first()->id;
        $pcs = Unit::where('abbreviation', 'pcs')->first()->id;

        $categories = [
            [
                'name' => 'Madera',
                'slug' => 'madera',
                'icon' => 'heroicon-o-squares-2x2',
                'default_unit_id' => $ft,
                'specifications_schema' => [
                    ['name' => 'type', 'label' => 'Tipo de Pieza', 'type' => 'select', 'options' => ['Post', 'Rail', 'Boards', 'Trim', 'Top Plate']],
                    ['name' => 'species', 'label' => 'Especie', 'type' => 'select', 'options' => ['Cedro', 'Pino', 'Redwood']],
                    ['name' => 'treatment', 'label' => 'Tratamiento', 'type' => 'select', 'options' => ['Presión Tratada (PT)', 'Natural/Sin Tratar', 'Pre-Teñida (Stained)']],
                    ['name' => 'nominal_size', 'label' => 'Tamaño Nominal', 'type' => 'select', 'options' => ['1x4x6', '1x6x6', '2x4x8', '4x4x8', '4x4x10', '6x6x8', 'Custom']],
                    ['name' => 'length', 'label' => 'Largo Específico', 'type' => 'text'],
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Aluminio',
                'slug' => 'aluminio',
                'icon' => 'heroicon-o-cog-6-tooth',
                'default_unit_id' => $ft,
                'specifications_schema' => [
                    ['name' => 'component_type', 'label' => 'Tipo de Componente', 'type' => 'select', 'options' => ['Panel', 'Line Post', 'End Post', 'Corner Post', 'Gate Post', 'Blank Post', 'Gate Standard', 'Gate Flush Bottom', 'Gate Arc']],
                    ['name' => 'finish', 'label' => 'Acabado/Color', 'type' => 'select', 'options' => ['Black', 'Zinc', 'Satin Black', 'Satin Zinc', 'Negro Mate', 'Bronce', 'Blanco', 'Galvanizado']],
                    ['name' => 'gauge', 'label' => 'Calibre/Grosor', 'type' => 'text'],
                    ['name' => 'height', 'label' => 'Altura', 'type' => 'select', 'options' => ['48"', '54"', '60"', '72"']],
                    ['name' => 'width', 'label' => 'Ancho', 'type' => 'text'],
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Vinyl',
                'slug' => 'vinyl',
                'icon' => 'heroicon-o-cube',
                'default_unit_id' => $ft,
                'specifications_schema' => [
                    ['name' => 'component', 'label' => 'Componente', 'type' => 'select', 'options' => ['Vinyl Post', 'Vinyl Rail', 'Vinyl Board']],
                    ['name' => 'color', 'label' => 'Color', 'type' => 'select', 'options' => ['Tan', 'Adobe', 'White']],
                    ['name' => 'style', 'label' => 'Estilo', 'type' => 'select', 'options' => ['Privacidad (Privacy)']],
                    ['name' => 'dimensions', 'label' => 'Dimensiones', 'type' => 'select', 'options' => ['4x4', '5x5', '1.5x5.5 Rail', 'T&G Board']],
                    ['name' => 'length', 'label' => 'Largo', 'type' => 'text'],
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Steel',
                'slug' => 'steel',
                'icon' => 'heroicon-o-wrench',
                'default_unit_id' => $pcs,
                'specifications_schema' => [
                    ['name' => 'component', 'label' => 'Componente', 'type' => 'select', 'options' => ['Post', 'Rail']],
                    ['name' => 'thickness_type', 'label' => 'Espesor/Tipo', 'type' => 'select', 'options' => ['SS20', 'SS40', 'Schedule 40']],
                    ['name' => 'finish', 'label' => 'Acabado/Color', 'type' => 'select', 'options' => ['Negro Mate', 'Bronce', 'Blanco', 'Galvanizado', 'Aluminio (Galv)', 'Negro']],
                    ['name' => 'gauge', 'label' => 'Calibre/Grosor', 'type' => 'text'],
                    ['name' => 'length', 'label' => 'Largo', 'type' => 'select', 'options' => ["6'", "7'", "8'", "9'", "10'", "12'", "21'", "24'"]],
                ],
                'sort_order' => 4,
            ],
            [
                'name' => 'Chain Link',
                'slug' => 'chain-link',
                'icon' => 'heroicon-o-link',
                'default_unit_id' => $ft,
                'specifications_schema' => [
                    ['name' => 'fabric_type', 'label' => 'Tipo Tejido', 'type' => 'select', 'options' => ['KK (Knuckle-Knuckle)', 'KT (Knuckle-Twist)']],
                    ['name' => 'gauge', 'label' => 'Calibre', 'type' => 'select', 'options' => ['9ga', '11ga', '11.5ga', '12ga', '6ga']],
                    ['name' => 'height', 'label' => 'Altura', 'type' => 'select', 'options' => ['42"', '48"', '60"', '72"', '84"', '96"', '144"']],
                    ['name' => 'color', 'label' => 'Color', 'type' => 'select', 'options' => ['Galvanized', 'Black', 'Green']],
                ],
                'sort_order' => 5,
            ],
            [
                'name' => 'Accesorios',
                'slug' => 'accesorios',
                'icon' => 'heroicon-o-star',
                'default_unit_id' => $pcs,
                'specifications_schema' => [
                    ['name' => 'parent_category', 'label' => 'Categoría Padre', 'type' => 'select', 'options' => ['Madera', 'Aluminio', 'Chain Link', 'Vinyl']],
                    ['name' => 'accessory_name', 'label' => 'Nombre Accesorio', 'type' => 'select', 'options' => ['Tapa (Cap)', 'Bisagra (Hinge)', 'Latch', 'Bracket', 'Tornillos (Box)', 'Drop Rod', 'Wheel']],
                    ['name' => 'description', 'label' => 'Descripción/SKU', 'type' => 'text'],
                ],
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
