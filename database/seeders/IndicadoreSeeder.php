<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndicadoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $indicadores = [
            ['name' => 'Ventas vs Presupuesto'],
            ['name' => 'Cotizaciones'],
            ['name' => 'Efectividad comercial'],
            ['name' => 'Clientes nuevos'],
            ['name' => 'Visitas / reuniones'],
        ];
        DB::table('indicadores')->insert($indicadores);
    }
}
