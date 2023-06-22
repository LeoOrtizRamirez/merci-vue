<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            ['name' => 'COMPONENTE ESTRÁTEGICO'],
            ['name' => 'CANALES DE VENTA'],
            ['name' => 'MECÁNICA COMERCIAL'],
            ['name' => 'TRANSFERENCIA DE CONOCIMIENTO'],
            ['name' => 'MERCADEO'],
            ['name' => 'INFRAESTRUCTURA'],
        ];
        DB::table('categorias')->insert($categorias);
    }
}
