<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['code' => 'R-01', 'rack' => 'Rack 1', 'bin' => 'A', 'description' => 'Rack 1, Bin A'],
            ['code' => 'R-02', 'rack' => 'Rack 1', 'bin' => 'B', 'description' => 'Rack 1, Bin B'],
            ['code' => 'A-12', 'rack' => 'Area A', 'bin' => '12', 'description' => 'Area A, Bin 12'],
            ['code' => 'Y-01', 'rack' => 'Yard', 'bin' => '1', 'description' => 'Yard Section 1'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
