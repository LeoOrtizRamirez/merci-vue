<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['name' => 'En proceso', 'color' => '#FFFFFF', 'backgroundColor' => '#6495ED', 'tipo' => 1],
            ['name' => 'Finalizada', 'color' => '#FFFFFF', 'backgroundColor' => '#DE3163', 'tipo' => 1],
            ['name' => 'Pausada', 'color' => '#FFFFFF', 'backgroundColor' => '#FFBF00', 'tipo' => 1],
            ['name' => 'Sin Iniciar', 'color' => '#FFFFFF', 'backgroundColor' => '#FFBF00', 'tipo' => 2],
            ['name' => 'Progreso', 'color' => '#FFFFFF', 'backgroundColor' => '#326fd1', 'tipo' => 2],
            ['name' => 'Terminada', 'color' => '#FFFFFF', 'backgroundColor' => '#1da750', 'tipo' => 2],
            ['name' => 'Vencida', 'color' => '#FFFFFF', 'backgroundColor' => '#DE3163', 'tipo' => 2],
        ];
        DB::table('estados')->insert($estados);
    }
}
