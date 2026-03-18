<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\States\EmployeeStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Info')
                ->columns(1)
                ->schema([
                    TextInput::make('first_name')
                        ->label('Nombre')
                        ->required(),
                    TextInput::make('last_name')
                        ->label('Apellido')
                        ->required(),
                    TextInput::make('identity_number')
                        ->label('Numero de Identidad')
                        ->maxLength(13)
                        ->unique(ignoreRecord:true)
                        ->validationMessages(['Este numero de identidad ya existe'])
                        ->required(),
                ]),

                Section::make('Employee Info')
                ->columns(3)
                ->schema([
                    TextInput::make('address_number')
                        ->label('Numero Direccion')
                        ->maxLength(9)
                        ->unique(ignoreRecord:true)
                        ->validationMessages(['Este numero de direccion ya existe'])
                        ->required(),
                    DatePicker::make('hiring_date')
                        ->label('Fecha de Contratacion')
                        ->required(),
                    DatePicker::make('anniversary_date')
                        ->label('Fecha de Aniversario')
                        ->required(),
                    Select::make('department_id')
                            ->relationship('department','name')
                            ->label('Departamento')
                            ->required(),
                    Select::make('employee_state')
                        ->label('Estado de Empleado')
                        ->options(EmployeeStatus::class)
                        ->required(),
                    Select::make('payroll_id')
                        ->label('Nomina')
                        ->relationship('payroll', 'payroll_type')
                        ->required(),
                    Select::make('user_id')
                            ->relationship('user','name')
                            ->label('Usuario')
                            ->required(),
                ]),
               
            ]);
    }
}
