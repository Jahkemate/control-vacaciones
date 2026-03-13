<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('employee.full_name')
                    ->relationship('employee','first_name')
                    ->label('Selecciona Jefe')
                    ->required(),
                TextInput::make('roles_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
