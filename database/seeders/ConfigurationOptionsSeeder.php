<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ConfigurationOption;
use Illuminate\Database\Seeder;

class ConfigurationOptionsSeeder extends Seeder
{
    /**
     * Seed the configuration options tree.
     * Basado en el documento técnico y el seeder actual de CategoriesSeeder
     */
    public function run(): void
    {
        // Obtener categorías existentes
        $madera = Category::where('slug', 'madera')->first();
        $aluminio = Category::where('slug', 'aluminio')->first();
        $steel = Category::where('slug', 'steel')->first();

        // ===== MADERA =====
        // Nivel 1: Raíz - Material
        $maderaRoot = ConfigurationOption::create([
            'parent_id' => null,
            'category_id' => $madera?->id,
            'name' => 'Madera',
            'next_step_label' => 'Tipo de Componente',
            'sku_part' => 'WD',
            'sort_order' => 1,
        ]);

        // Nivel 2: Tipo de Componente
        $poste = ConfigurationOption::create([
            'parent_id' => $maderaRoot->id,
            'category_id' => $madera?->id,
            'name' => 'Poste',
            'next_step_label' => 'Tipo de Madera',
            'sku_part' => 'PST',
            'sort_order' => 1,
        ]);

        $riel = ConfigurationOption::create([
            'parent_id' => $maderaRoot->id,
            'category_id' => $madera?->id,
            'name' => 'Riel',
            'next_step_label' => 'Tipo de Madera',
            'sku_part' => 'RIL',
            'sort_order' => 2,
        ]);

        $boards = ConfigurationOption::create([
            'parent_id' => $maderaRoot->id,
            'category_id' => $madera?->id,
            'name' => 'Boards',
            'next_step_label' => 'Tipo de Madera',
            'sku_part' => 'BRD',
            'sort_order' => 3,
        ]);

        // Nivel 3: Especies (para Poste)
        $cedro = ConfigurationOption::create([
            'parent_id' => $poste->id,
            'category_id' => $madera?->id,
            'name' => 'Cedro',
            'next_step_label' => 'Seleccione Tratamiento',
            'sku_part' => 'CDR',
            'sort_order' => 1,
        ]);

        $pino = ConfigurationOption::create([
            'parent_id' => $poste->id,
            'category_id' => $madera?->id,
            'name' => 'Pino',
            'next_step_label' => 'Seleccione Tratamiento',
            'sku_part' => 'PIN',
            'sort_order' => 2,
        ]);

        $redwood = ConfigurationOption::create([
            'parent_id' => $poste->id,
            'category_id' => $madera?->id,
            'name' => 'Redwood',
            'next_step_label' => 'Seleccione Tratamiento',
            'sku_part' => 'RDW',
            'sort_order' => 3,
        ]);

        // Nivel 4: Tratamiento (para Cedro)
        $pt = ConfigurationOption::create([
            'parent_id' => $cedro->id,
            'category_id' => $madera?->id,
            'name' => 'Presión Tratada (PT)',
            'next_step_label' => 'Seleccione Tamaño',
            'sku_part' => 'PT',
            'sort_order' => 1,
        ]);

        $natural = ConfigurationOption::create([
            'parent_id' => $cedro->id,
            'category_id' => $madera?->id,
            'name' => 'Natural/Sin Tratar',
            'next_step_label' => 'Seleccione Tamaño',
            'sku_part' => 'NAT',
            'sort_order' => 2,
        ]);

        // Nivel 5: Tamaños (para PT)
        $size4x4x8 = ConfigurationOption::create([
            'parent_id' => $pt->id,
            'category_id' => $madera?->id,
            'name' => '4x4x8',
            'next_step_label' => null, // FIN - Es hoja terminal
            'sku_part' => '4X4X8',
            'sort_order' => 1,
        ]);

        $size4x4x10 = ConfigurationOption::create([
            'parent_id' => $pt->id,
            'category_id' => $madera?->id,
            'name' => '4x4x10',
            'next_step_label' => null,
            'sku_part' => '4X4X10',
            'sort_order' => 2,
        ]);

        $size6x6x8 = ConfigurationOption::create([
            'parent_id' => $pt->id,
            'category_id' => $madera?->id,
            'name' => '6x6x8',
            'next_step_label' => null,
            'sku_part' => '6X6X8',
            'sort_order' => 3,
        ]);

        // ===== ACERO (STEEL) =====
        $aceroRoot = ConfigurationOption::create([
            'parent_id' => null,
            'category_id' => $steel?->id,
            'name' => 'Acero',
            'next_step_label' => 'Tipo de Componente',
            'sku_part' => 'ST',
            'sort_order' => 2,
        ]);

        // Nivel 2: Componentes
        $steelPoste = ConfigurationOption::create([
            'parent_id' => $aceroRoot->id,
            'category_id' => $steel?->id,
            'name' => 'Poste',
            'next_step_label' => 'Seleccione Espesor',
            'sku_part' => 'PST',
            'sort_order' => 1,
        ]);

        $steelRail = ConfigurationOption::create([
            'parent_id' => $aceroRoot->id,
            'category_id' => $steel?->id,
            'name' => 'Riel',
            'next_step_label' => 'Seleccione Espesor',
            'sku_part' => 'RIL',
            'sort_order' => 2,
        ]);

        // Nivel 3: Espesor
        $ss20 = ConfigurationOption::create([
            'parent_id' => $steelPoste->id,
            'category_id' => $steel?->id,
            'name' => 'SS20',
            'next_step_label' => 'Seleccione Acabado',
            'sku_part' => 'SS20',
            'sort_order' => 1,
        ]);

        $ss40 = ConfigurationOption::create([
            'parent_id' => $steelPoste->id,
            'category_id' => $steel?->id,
            'name' => 'SS40',
            'next_step_label' => 'Seleccione Acabado',
            'sku_part' => 'SS40',
            'sort_order' => 2,
        ]);

        // Nivel 4: Acabado
        $negroMate = ConfigurationOption::create([
            'parent_id' => $ss20->id,
            'category_id' => $steel?->id,
            'name' => 'Negro Mate',
            'next_step_label' => 'Seleccione Largo',
            'sku_part' => 'BLK',
            'sort_order' => 1,
        ]);

        $galvanizado = ConfigurationOption::create([
            'parent_id' => $ss20->id,
            'category_id' => $steel?->id,
            'name' => 'Galvanizado',
            'next_step_label' => 'Seleccione Largo',
            'sku_part' => 'GALV',
            'sort_order' => 2,
        ]);

        // Nivel 5: Largos (hojas finales)
        ConfigurationOption::create([
            'parent_id' => $negroMate->id,
            'category_id' => $steel?->id,
            'name' => "6'",
            'next_step_label' => null,
            'sku_part' => '6FT',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'parent_id' => $negroMate->id,
            'category_id' => $steel?->id,
            'name' => "8'",
            'next_step_label' => null,
            'sku_part' => '8FT',
            'sort_order' => 2,
        ]);

        ConfigurationOption::create([
            'parent_id' => $negroMate->id,
            'category_id' => $steel?->id,
            'name' => "10'",
            'next_step_label' => null,
            'sku_part' => '10FT',
            'sort_order' => 3,
        ]);

        // ===== ALUMINIO - Caso Asimétrico =====
        if ($aluminio) {
            $aluminioRoot = ConfigurationOption::create([
                'parent_id' => null,
                'category_id' => $aluminio->id,
                'name' => 'Aluminio',
                'next_step_label' => 'Tipo de Componente',
                'sku_part' => 'AL',
                'sort_order' => 3,
            ]);

            // Panel (Camino corto: 4 niveles)
            $panel = ConfigurationOption::create([
                'parent_id' => $aluminioRoot->id,
                'category_id' => $aluminio->id,
                'name' => 'Panel',
                'next_step_label' => 'Seleccione Color',
                'sku_part' => 'PNL',
                'sort_order' => 1,
            ]);

            $panelBlack = ConfigurationOption::create([
                'parent_id' => $panel->id,
                'category_id' => $aluminio->id,
                'name' => 'Black',
                'next_step_label' => 'Seleccione Altura',
                'sku_part' => 'BLK',
                'sort_order' => 1,
            ]);

            ConfigurationOption::create([
                'parent_id' => $panelBlack->id,
                'category_id' => $aluminio->id,
                'name' => '48"',
                'next_step_label' => null, // FIN
                'sku_part' => '48IN',
                'sort_order' => 1,
            ]);

            ConfigurationOption::create([
                'parent_id' => $panelBlack->id,
                'category_id' => $aluminio->id,
                'name' => '72"',
                'next_step_label' => null,
                'sku_part' => '72IN',
                'sort_order' => 2,
            ]);

            // Line Post (Camino largo: 5 niveles)
            $linePost = ConfigurationOption::create([
                'parent_id' => $aluminioRoot->id,
                'category_id' => $aluminio->id,
                'name' => 'Line Post',
                'next_step_label' => 'Seleccione Color',
                'sku_part' => 'LPS',
                'sort_order' => 2,
            ]);

            $linePostZinc = ConfigurationOption::create([
                'parent_id' => $linePost->id,
                'category_id' => $aluminio->id,
                'name' => 'Zinc',
                'next_step_label' => 'Seleccione Altura',
                'sku_part' => 'ZNC',
                'sort_order' => 1,
            ]);

            $linePost60 = ConfigurationOption::create([
                'parent_id' => $linePostZinc->id,
                'category_id' => $aluminio->id,
                'name' => '60"',
                'next_step_label' => 'Seleccione Calibre',
                'sku_part' => '60IN',
                'sort_order' => 1,
            ]);

            ConfigurationOption::create([
                'parent_id' => $linePost60->id,
                'category_id' => $aluminio->id,
                'name' => '18 Gauge',
                'next_step_label' => null, // FIN
                'sku_part' => '18GA',
                'sort_order' => 1,
            ]);
        }

        // ============================================
        // VINYL (Category ID: 3)
        // ============================================
        $vinylRoot = ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => null,
            'name' => 'Vinyl',
            'next_step_label' => 'Seleccione Tipo',
            'sku_part' => 'VNL',
            'sort_order' => 1,
        ]);

        $panel = ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => $vinylRoot->id,
            'name' => 'Panel',
            'next_step_label' => 'Seleccione Color',
            'sku_part' => 'PNL',
            'sort_order' => 1,
        ]);

        $white = ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => $panel->id,
            'name' => 'White',
            'next_step_label' => 'Seleccione Altura',
            'sku_part' => 'WHT',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => $white->id,
            'name' => '4 ft',
            'next_step_label' => null,
            'sku_part' => '4FT',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => $white->id,
            'name' => '6 ft',
            'next_step_label' => null,
            'sku_part' => '6FT',
            'sort_order' => 2,
        ]);

        $tan = ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => $panel->id,
            'name' => 'Tan',
            'next_step_label' => 'Seleccione Altura',
            'sku_part' => 'TAN',
            'sort_order' => 2,
        ]);

        ConfigurationOption::create([
            'category_id' => 3,
            'parent_id' => $tan->id,
            'name' => '4 ft',
            'next_step_label' => null,
            'sku_part' => '4FT',
            'sort_order' => 1,
        ]);

        // ============================================
        // CHAIN LINK (Category ID: 5)
        // ============================================
        $chainRoot = ConfigurationOption::create([
            'category_id' => 5,
            'parent_id' => null,
            'name' => 'Chain Link',
            'next_step_label' => 'Seleccione Calibre',
            'sku_part' => 'CHL',
            'sort_order' => 1,
        ]);

        $gauge9 = ConfigurationOption::create([
            'category_id' => 5,
            'parent_id' => $chainRoot->id,
            'name' => '9 Gauge',
            'next_step_label' => 'Seleccione Acabado',
            'sku_part' => '9GA',
            'sort_order' => 1,
        ]);

        $galvanized = ConfigurationOption::create([
            'category_id' => 5,
            'parent_id' => $gauge9->id,
            'name' => 'Galvanized',
            'next_step_label' => 'Seleccione Altura',
            'sku_part' => 'GLV',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 5,
            'parent_id' => $galvanized->id,
            'name' => '4 ft',
            'next_step_label' => null,
            'sku_part' => '4FT',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 5,
            'parent_id' => $galvanized->id,
            'name' => '6 ft',
            'next_step_label' => null,
            'sku_part' => '6FT',
            'sort_order' => 2,
        ]);

        // ============================================
        // ACCESORIOS (Category ID: 6)
        // ============================================
        $accRoot = ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => null,
            'name' => 'Accesorios',
            'next_step_label' => 'Seleccione Tipo',
            'sku_part' => 'ACC',
            'sort_order' => 1,
        ]);

        $brackets = ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => $accRoot->id,
            'name' => 'Brackets',
            'next_step_label' => 'Seleccione Material',
            'sku_part' => 'BRK',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => $brackets->id,
            'name' => 'Steel',
            'next_step_label' => null,
            'sku_part' => 'STL',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => $brackets->id,
            'name' => 'Aluminum',
            'next_step_label' => null,
            'sku_part' => 'ALU',
            'sort_order' => 2,
        ]);

        $gates = ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => $accRoot->id,
            'name' => 'Gates',
            'next_step_label' => 'Seleccione Ancho',
            'sku_part' => 'GAT',
            'sort_order' => 2,
        ]);

        ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => $gates->id,
            'name' => '3 ft',
            'next_step_label' => null,
            'sku_part' => '3FT',
            'sort_order' => 1,
        ]);

        ConfigurationOption::create([
            'category_id' => 6,
            'parent_id' => $gates->id,
            'name' => '4 ft',
            'next_step_label' => null,
            'sku_part' => '4FT',
            'sort_order' => 2,
        ]);

        $this->command->info('✅ Configuration options seeded successfully!');
        $this->command->info('   - Madera: 5 niveles de profundidad');
        $this->command->info('   - Acero: 5 niveles de profundidad');
        $this->command->info('   - Aluminio: Asimétrico (Panel: 4 niveles, Line Post: 5 niveles)');
        $this->command->info('   - Vinyl: 4 niveles de profundidad');
        $this->command->info('   - Chain Link: 4 niveles de profundidad');
        $this->command->info('   - Accesorios: 3 niveles de profundidad');
    }
}
