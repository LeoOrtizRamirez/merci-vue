<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresas = [
            ['name' => 'MERCI', 'nit' => '901.137.860-5', 'estado_id' => 1, 'logo' => 'logo-merci.png'] // En proceso,
        ];
        DB::table('empresas')->insert($empresas);
    }
}
