<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(EstadoSeeder::class);
        $this->call(IndicadoreSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ActividadSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(ModelHasRoleSeeder::class);
        $this->call(RoleHasPermissionSeeder::class);
    }
}
