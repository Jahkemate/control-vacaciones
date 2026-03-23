<?php

namespace App\Filament\Resources\VacationRequests\Schemas;

use App\Models\BalanceVacation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Column;

class VacationRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información para Solicitud de Vacaciones')
                    ->columns(1)
                    ->schema([
                        Select::make('employee_id')
                            ->label('Empleado Solicitante')
                            ->relationship('employee', 'first_name', fn($query) => $query->whereHas('BalanceVacation')) //Para que somo me muestre los empleadfos que tiene balance
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                $record->first_name . ' ' . $record->last_name
                            )
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {

                                $balance = BalanceVacation::where('employee_id', $state)->first(); //Esto solo me mostrara los empleados que tiene balance de vacaciones

                                if ($balance) {
                                    $set('balance', $balance->balance);
                                }
                            }),
                        DatePicker::make('start_date')
                            ->label('Fecha de Inicio')
                            ->date(),
                        DatePicker::make('end_date')
                            ->label('Fecha de Inicio'),
                        TextInput::make('total_business_days')
                            ->dehydrated(false) // para que el valor del campo no se envie ni se guarde en la base de datos (temporal)
                            ->label('Total de Dias Habiles'),
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Balance')
                            ->columns(1)
                            ->schema([
                                TextInput::make('balance')
                                    ->readOnly()
                                    ->label('Vacaciones'),
                            ]),
                        Section::make('Informacion Adicional')
                            ->columns(1)
                            ->schema([
                                Textarea::make('comment')
                                    ->label('Comentario')
                            ])
                    ]),
            ]);
    }
}
