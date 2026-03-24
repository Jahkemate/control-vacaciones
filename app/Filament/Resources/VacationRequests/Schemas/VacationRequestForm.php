<?php

namespace App\Filament\Resources\VacationRequests\Schemas;

use App\Models\BalanceVacation;
use App\States\RequestStatus;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                            ->relationship('employee', 'first_name', fn($query) => $query->whereHas('BalanceVacation')) //Para que solo me muestre los empleadfos que tiene balance
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
                        Select::make('state')
                            ->label('Estado de la Solicitud')
                            ->options(RequestStatus::class)
                            ->default(RequestStatus::Draft),
                        DatePicker::make('start_date')
                            ->label('Fecha de Inicio')
                            ->reactive()
                            ->date()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $fechaInicio = $state;
                                $fechaFin = $get('end_date');

                                if ($fechaInicio && $fechaFin) {
                                    $diasHabiles = 0;
                                    $inicio = Carbon::parse($fechaInicio);
                                    $fin = Carbon::parse($fechaFin);

                                    while ($inicio->lte($fin)) {
                                        if (!$inicio->isWeekend()) {
                                            $diasHabiles++;
                                        }
                                        $inicio->addDay();
                                    }

                                    $set('total_business_days', $diasHabiles); // guardamos el resultado
                                }
                            }),
                        DatePicker::make('end_date')
                            ->label('Fecha Final')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $fechaInicio = $get('start_date');
                                $fechaFin = $state;

                                if ($fechaInicio && $fechaFin) {
                                    $diasHabiles = 0;
                                    $inicio = Carbon::parse($fechaInicio);
                                    $fin = Carbon::parse($fechaFin);

                                    while ($inicio->lte($fin)) {
                                        if (!$inicio->isWeekend()) {
                                            $diasHabiles++;
                                        }
                                        $inicio->addDay();
                                    }

                                    $set('total_business_days', $diasHabiles);
                                }
                            }),
                        TextInput::make('total_business_days')
                            ->label('Total de Dias Habiles')
                            ->readOnly(),
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
