<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = new Department();
        $department->id = '1';
        $department->name = 'IT';
        $department->employee_id = '1';
        $department->roles_id = '1';
        $department->save();
    }
}
