<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actividades = [
            ['name' => 'Diágnostico', 'categoria_id'=>'1'],
            ['name' => 'Mapa táctico', 'categoria_id'=>'1'],
            ['name' => 'Pruebas IPV', 'categoria_id'=>'1'],
            ['name' => 'Cronograma', 'categoria_id'=>'1'],
            ['name' => 'Estudio de competencia', 'categoria_id'=>'1'],
            ['name' => 'Cliente incógnito', 'categoria_id'=>'1'],
            ['name' => 'Herramientas comerciales', 'categoria_id'=>'3'],
            ['name' => 'Buyer persona', 'categoria_id'=>'3'],
            ['name' => 'Diferencial', 'categoria_id'=>'3'],
            ['name' => 'Flywheel', 'categoria_id'=>'3'],
            ['name' => 'Árbol de objeciones y preguntas frecuentes', 'categoria_id'=>'3'],
            ['name' => 'Guiones y plantillas', 'categoria_id'=>'3'],
            ['name' => 'Rutas de seguimiento', 'categoria_id'=>'3'],
        ];
        DB::table('actividades')->insert($actividades);
    }
}
