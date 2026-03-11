<?php

namespace App\Filament\Resources\Employees\Schemas;

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
                ->columns(3)
                ->schema([
                    TextInput::make('first_name')
                        ->required(),
                    TextInput::make('last_name')
                        ->required(),
                    TextInput::make('identity_number')
                        ->required(),
                ]),

                Section::make('Employee Info')
                ->columns(3)
                ->schema([
                    TextInput::make('address_number')
                        ->required(),
                    DatePicker::make('hiring_date')
                        ->required(),
                    DatePicker::make('anniversary_date')
                        ->required(),
                    Select::make('department_id')
                            ->relationship('department','name')
                            ->label('Departamento')
                            ->required(),
                    TextInput::make('employee_state')
                        ->required(),
                    TextInput::make('payroll_id')
                        ->required()
                        ->numeric(),
                    Select::make('user_id')
                            ->relationship('user','name')
                            ->label('Usuario')
                            ->required(),
                ]),
               
            ]);
    }
}
