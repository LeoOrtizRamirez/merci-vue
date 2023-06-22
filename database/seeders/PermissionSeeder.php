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
        ];
        DB::table('permissions')->insert($permissions);
    }
}
