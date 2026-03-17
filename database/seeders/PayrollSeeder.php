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
        $payroll->vacations_days = '10';
        $payroll->vacations_bonus = 'no';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = '2do. Año Legal';
        $payroll->vacations_days = '12';
        $payroll->vacations_bonus = 'no';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = '3er. Año Legal';
        $payroll->vacations_days = '18';
        $payroll->vacations_bonus = 'no';
        $payroll->save();

        $payroll = new Payroll();
        $payroll->payroll_type = '4to. Año Legal';
        $payroll->vacations_days = '20';
        $payroll->vacations_bonus = 'no';
        $payroll->save();
    }
}
