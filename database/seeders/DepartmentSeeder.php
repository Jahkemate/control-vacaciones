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
        $department->save();

        $department = new Department();
        $department->name = 'Administracion';
        $department->save();

        $department = new Department();
        $department->name = 'RRHH';
        $department->save();

        $department = new Department();
        $department->name = 'Administracion';
        $department->save();

        $department = new Department();
        $department->name = 'Almacen';
        $department->save();

        $department = new Department();
        $department->name = 'Biomedico';
        $department->save();

        $department = new Department();
        $department->name = 'Compras';
        $department->save();

        $department = new Department();
        $department->name = 'Contabilidad';
        $department->save();

        $department = new Department();
        $department->name = 'Contraloria HVD';
        $department->save();

        $department = new Department();
        $department->name = 'Dietetica';
        $department->save();

        $department = new Department();
        $department->name = 'Direccion Medica';
        $department->save();

        $department = new Department();
        $department->name = 'Facturacion';
        $department->save();

        $department = new Department();
        $department->name = 'Farmacia';
        $department->save();

        $department = new Department();
        $department->name = 'Fisioterapia';
        $department->save();

        $department = new Department();
        $department->name = 'IT';
        $department->save();

        $department = new Department();
        $department->name = 'Laboratorio';
        $department->save();

        $department = new Department();
        $department->name = 'Rayos X';
        $department->save();

        $department = new Department();
        $department->name = 'Mantenimiento';
        $department->save();

        $department = new Department();
        $department->name = 'Recursos Humanos';
        $department->save();

        $department = new Department();
        $department->name = 'Registros Medicos';
        $department->save();

        $department = new Department();
        $department->name = 'Tesoreria';
        $department->save();

        $department = new Department();
        $department->name = 'Cuerpo Medico';
        $department->save();

        $department = new Department();
        $department->name = 'Sala Operacione';
        $department->save();

        $department = new Department();
        $department->name = 'Enfermeria';
        $department->save();
    }
}
