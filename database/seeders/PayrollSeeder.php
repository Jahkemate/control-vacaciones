<?php

namespace Database\Seeders;

use App\Models\Payroll;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payroll = new Payroll();
        $payroll->payroll_type = '1er. Año Legal';
        $payroll->vacations_days = '9';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = '2do. Año Legal';
        $payroll->vacations_days = '10';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = '3er. Año Legal';
        $payroll->vacations_days = '13';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = '4to. Año Legal';
        $payroll->vacations_days = '17';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Confidencial';
        $payroll->vacations_days = '22';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Enfermeras Profesionales <5to. Año';
        $payroll->vacations_days = '22';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Enfermeras Profesionales <5 Años 2 Semanas Pagadas';
        $payroll->vacations_days = '20';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Enfermeras Profesionales <5 Años 6 Semanas Vac.';
        $payroll->vacations_days = '30';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Farmacéuticos';
        $payroll->vacations_days = '30';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Medico con Guardia Medica';
        $payroll->vacations_days = '30';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Medicos';
        $payroll->vacations_days = '30';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Medicos 1er Año';
        $payroll->vacations_days = '12';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Medicos 2do Año';
        $payroll->vacations_days = '15';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Medicos 3er y 4to Año';
        $payroll->vacations_days = '20';
        $payroll->vacations_bonus = 'No';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = 'Microbiologas';
        $payroll->vacations_days = '30';
        $payroll->vacations_bonus = 'No';
        $payroll->save();
    }
}
