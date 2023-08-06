<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_empresa = [
            [
                'user_id' => 1,
                'empresa_id' => 1,
            ]
        ];
        DB::table('user_empresas')->insert($user_empresa);
    }
}
