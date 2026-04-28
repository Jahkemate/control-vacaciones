<?php

namespace App\Filament\Resources\Users\Schemas;

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
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de Usuario')
                            ->required(),
                        TextInput::make('email')
                            ->label('Correo')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->revealable()
                            ->password()
                            ->required(fn($context) => $context === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null),
                    ]),

                Section::make('Rol Info')
                    ->columns(1)
                    ->schema([
                        Select::make('role')
                            ->label('Rol del Usuario')
                            ->options([
                                'employee' => 'Empleado',
                                'admin' => 'Administrador',
                                'manager' => 'Jefe'
                            ])
                            ->required()
                    ]),

            ]);
    }
}
