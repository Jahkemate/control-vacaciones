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
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

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
                            ->default(fn() => Auth::user()->employee?->id) // Establece el valor predeterminado al primer empleado del usuario autenticado
                            ->disabled()
                            ->dehydrated()
                            ->relationship('employee', 'first_name', fn($query) => $query->whereHas('BalanceVacation')) //Para que solo me muestre los empleadfos que tiene balance
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                $record->first_name . ' ' . $record->last_name
                            )
                            ->reactive(),
                        Select::make('status')
                            ->disabled()
                            ->reactive()
                            ->label('Estado de la Solicitud')
                            ->options(RequestStatus::class)
                            ->default(RequestStatus::Draft),

                        Section::make('Fechas de Vacaciones')
                            ->columns(2)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Fecha de Inicio')
                                    ->required()
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager
                                    ]))
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
                                    //->default(fn () => now()->format('Y-m-d'))
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager
                                    ]))
                                    ->required()
                                    ->date()
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
                            ]),

                        TextInput::make('total_business_days')
                            ->reactive()
                            ->label('Total de Dias Habiles')
                            ->readOnly(),
                    ]),

                Grid::make(1)
                    ->schema([
                        Section::make('Balance')
                            ->columns(1)
                            ->schema([
                                TextInput::make('balance')
                                    ->reactive()
                                    ->readOnly()
                                    ->visibleOn('create', 'edit')
                                    ->label('Vacaciones')
                                    ->placeholder('Vacaciones disponibles')
                                    ->default(
                                        fn($get) =>
                                        BalanceVacation::where('employee_id', $get('employee_id'))->value('balance') // Obtiene el balance de vacaciones del empleado seleccionado
                                    ),
                            ]),
                        Section::make('Informacion Adicional')
                            ->columns(1)
                            ->schema([
                                Textarea::make('comment')
                                    ->label('Comentario o Justificacion')
                                    ->placeholder('Breve de Descripcion (Opcional)')
                            ])
                    ]),
            ]);
    }
}
