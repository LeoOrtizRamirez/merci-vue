<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'role.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'role.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'customer.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'empresa.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'empresa.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'empresa.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'empresa.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'categoria.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'categoria.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'categoria.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'categoria.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rol.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rol.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rol.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rol.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'acta.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'acta.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'acta.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'acta.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'actividad.create', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'actividad.show', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'actividad.edit', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'actividad.destroy', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('permissions')->insert($permissions);
    }
}
