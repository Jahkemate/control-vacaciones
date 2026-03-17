<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use function Laravel\Prompts\select;

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
                ->columns(1)
                ->schema([
                        Select::make('role_id')
                        ->relationship('role', 'role_name')
                        ->label('Rol')
                        ->required(),
                ]),
             
            ]);
    }
}
