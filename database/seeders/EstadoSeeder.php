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
            ['name' => 'En proceso', 'color' => '#FFFFFF', 'backgroundColor' => '#6495ED'],
            ['name' => 'Finalizada', 'color' => '#FFFFFF', 'backgroundColor' => '#DE3163'],
            ['name' => 'Pausada', 'color' => '#FFFFFF', 'backgroundColor' => '#FFBF00'],
        ];
        DB::table('estados')->insert($estados);
    }
}
