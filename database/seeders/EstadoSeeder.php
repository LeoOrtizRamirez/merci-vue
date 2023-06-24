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
            ['name' => 'En proceso'],
            ['name' => 'Finalizada'],
            ['name' => 'Pausada'],
        ];
        DB::table('estados')->insert($estados);
    }
}
