<?php

namespace App\Filament\Resources\Departments\Schemas;

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
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('roles_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
