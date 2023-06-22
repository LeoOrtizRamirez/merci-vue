<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'ADMIN', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CLIENTE', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'CONSULTOR', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);
    }
}
