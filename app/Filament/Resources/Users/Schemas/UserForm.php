<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Info')
                ->columns(3)
                ->schema([
                    Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->label('Nombre')
                    ->required(),
                    TextInput::make('email')
                        ->label('Correo')
                        ->email()
                        ->required(),
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->required(),
                ]),

                Section::make('Rol Info')
                ->columns(2)
                ->schema([
                        TextInput::make('role_id')
                        ->label('Rol')
                        ->required(),
                        Select::make('department_id')
                            ->relationship('department','name')
                            ->label('Departamento')
                            ->required(),
                ]),
             
            ]);
    }
}
