<?php

namespace App\Filament\Resources\TypeOfPayrolls\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TypeOfPayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('payroll_type')
                    ->label('Ingrese Tipo de Nomina')
                    ->required(),
                TextInput::make('vacations_days')
                    ->label('Dias que Corresponden')
                    ->required(),
                Select::make('vacations_bonus')
                    ->label('Bonus')
                    ->options([
                        'Si' => 'Si',
                        'No' => 'No',
                    ])
                    ->required(),
            ]);
    }
}
