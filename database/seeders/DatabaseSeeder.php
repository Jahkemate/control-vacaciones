<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Crear usuario
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'pruebas@example.com',
            'password' => Hash::make('123456'), 
        ]);

        // Crear rol admin si no existe
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Darle todos los permisos
        $role->syncPermissions(Permission::all());

        // Asignar rol al usuario
        $user->assignRole($role);

        //Se llama a los seeders para ejecutarlos
        $this->call(DepartmentSeeder::class);
        $this->call(PayrollSeeder::class);
    }
}
