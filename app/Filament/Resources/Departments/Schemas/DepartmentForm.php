<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Employee;
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
/*                 Select::make('employee_id')
                    ->relationship(
                        'employee',
                        modifyQueryUsing: fn ($query) => $query->where('employee_state','activo') // esta es una validacion que permite solo mostrar los usuarios activos
                        )
                    ->getOptionLabelFromRecordUsing(fn ( Employee $record) => "{$record->first_name} {$record->last_name}")// Este metodo permite traer el first_name y el last_name del modelo Employee, para que se muestre como nombre completo
                    ->label('Selecciona Jefe')
                    ->helperText('Aqui solo se mostraran usuarios activos')
                    ->required(), */
            ]);
    }
}
