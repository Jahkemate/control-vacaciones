<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = new Department();
        $department->name = 'IT';
        $department->employee_id = '1';
        $department->roles_id = '1';
        $department->save();

        $department = new Department();
        $department->name = 'Administracion';
        $department->employee_id = '2';
        $department->roles_id = '1';
        $department->save();

        $department = new Department();
        $department->name = 'RRHH';
        $department->employee_id = '3';
        $department->roles_id = '1';
        $department->save();
    }

    
}
