<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'RRHH',
            'email' => 'pruebas@example.com',
            'role' => 'admin',
            'password' => '123456'
        ]);

        //Se llama a los seeders para ejecutarlos
         $this->call(DepartmentSeeder::class);
         $this->call(PayrollSeeder::class);
    }
}
