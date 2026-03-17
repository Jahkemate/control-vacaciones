<?php

namespace App\Filament\Resources\BalanceVacations\Schemas;

use App\Models\Employee;
use App\States\EmployeeStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BalanceVacationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([  Section::make('Personal Info')
                ->columns(1)
                ->schema([
                    Select::make('employee_id')
                        ->relationship('employee', 'first_name')
                        ->getOptionLabelFromRecordUsing(fn ($record) => 
                                $record->first_name . ' ' . $record->last_name
                            )
                        ->label('Nombre Empleado')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {

                                    $employee = Employee::find($state);

                                    if ($employee) {
                                        $set('identity_number', $employee->identity_number);
                                        $set('address_number', $employee->address_number);
                                        $set('hiring_date', $employee->hiring_date);
                                        $set('anniversary_date', $employee->anniversary_date);
                                        $set('employee_state', $employee->employee_state);
                                        $set('department_id', $employee->department);
                                        $set('payroll_id', $employee->payroll);
                                        $set('user_id', $employee->user);
                                    }
                                })
                        ->required(),
                    TextInput::make('identity_number')
                        ->disabled()
                        ->dehydrated(false),
                        TextInput::make('address_number')
                        ->disabled()
                        ->dehydrated(false),
                    DatePicker::make('hiring_date')
                        ->disabled()
                        ->dehydrated(false),
                    DatePicker::make('anniversary_date')
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('department_id')
                            ->relationship('department','name')
                            ->label('Departamento')
                            //->disabled()
                            ->dehydrated(false),
                    Select::make('employee_state')
                        ->options(EmployeeStatus::class)
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('payroll_id')
                        ->relationship('payroll', 'payroll_type')
                        //->disabled()
                        ->dehydrated(false),
                    Select::make('user_id')
                            ->relationship('user','name')
                            ->label('Usuario')
                            //->disabled()
                            ->dehydrated(false),
                ]),

                Section::make('Balance Info')
                ->columns(1)
                ->schema([
                    
                ]),
               
            ]);
                
    }
}
