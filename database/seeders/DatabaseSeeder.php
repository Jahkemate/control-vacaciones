<?php


namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Genera permisos de Shield desde los resources, pages y widgets actuales del panel
        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'control_de_vacaciones',
            '--no-interaction' => true,
        ]);


        // Limpia la caché de permisos para asegurarse de que los cambios se reflejen inmediatamente.
        app(PermissionRegistrar::class)->forgetCachedPermissions();


        // Crea los roles funcionales que reemplazan el antiguo campo users.role.
        $superAdminRole = Role::findOrCreate('super_admin', 'web');


        // Crea el usuario inicial que ser el superadmin
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);


        // Asigna super_admin desde Shield para que el usuario inicial tenga acceso total.
        $superAdmin->assignRole($superAdminRole);


        $superAdmin->syncPermissions(Permission::all());


        //Se llama a los seeders para ejecutarlos
        $this->call(DepartmentSeeder::class);
        $this->call(PayrollSeeder::class);
    }
}
