<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Piezas', 'abbreviation' => 'pcs', 'is_base_unit' => true],
            ['name' => 'Pies', 'abbreviation' => 'ft', 'is_base_unit' => false],
            ['name' => 'Cajas', 'abbreviation' => 'box', 'is_base_unit' => false],
            ['name' => 'Pallet', 'abbreviation' => 'plt', 'is_base_unit' => false],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
