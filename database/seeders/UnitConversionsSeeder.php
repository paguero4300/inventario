<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use App\Models\UnitConversion;
use Illuminate\Database\Seeder;

class UnitConversionsSeeder extends Seeder
{
    public function run(): void
    {
        $pcs = Unit::where('abbreviation', 'pcs')->first()->id;
        $ft = Unit::where('abbreviation', 'ft')->first()->id;
        $box = Unit::where('abbreviation', 'box')->first()->id;
        $plt = Unit::where('abbreviation', 'plt')->first()->id;

        // Global Conversions
        UnitConversion::create([
            'category_id' => null,
            'from_unit_id' => $ft,
            'to_unit_id' => $pcs,
            'conversion_factor' => 1.0000,
        ]);

        // Category Specific Conversions
        $madera = Category::where('slug', 'madera')->first();
        if ($madera) {
            UnitConversion::create([
                'category_id' => $madera->id,
                'from_unit_id' => $box,
                'to_unit_id' => $pcs,
                'conversion_factor' => 50.0000,
            ]);
            UnitConversion::create([
                'category_id' => $madera->id,
                'from_unit_id' => $plt,
                'to_unit_id' => $pcs,
                'conversion_factor' => 500.0000,
            ]);
        }

        $accesorios = Category::where('slug', 'accesorios')->first();
        if ($accesorios) {
            UnitConversion::create([
                'category_id' => $accesorios->id,
                'from_unit_id' => $box,
                'to_unit_id' => $pcs,
                'conversion_factor' => 100.0000,
            ]);
        }

        $chainLink = Category::where('slug', 'chain-link')->first();
        if ($chainLink) {
            UnitConversion::create([
                'category_id' => $chainLink->id,
                'from_unit_id' => $ft,
                'to_unit_id' => $pcs,
                'conversion_factor' => 1.0000,
            ]);
        }
    }
}
