<?php

namespace App\Filament\Resources\PaidRequests\Schemas;

use App\Models\BalanceVacation;
use App\States\RequestStatus;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class PaidRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->schema([
                        Section::make('Informacion de la Solicitud')
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
                                    ->dehydrated()
                                    ->reactive()
                                    ->label('Estado de la Solicitud')
                                    ->options(RequestStatus::class)
                                    ->default(RequestStatus::Draft),

                                DateTimePicker::make('request_date')
                                    ->label('Fecha de Creacion')
                                    ->readOnly()
                                    ->default(now())
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager
                                    ]))
                                    ->dehydrated()
                                    ->required(),
                                TextInput::make('days_to_compensate')
                                    ->label('Dias a Compensar')
                                    ->helperText('Dias habiles')
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager
                                    ]))
                                    ->dehydrated()
                                    ->numeric()
                                    ->minValue('0')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('paid_accrued', $state);
                                        $set('paid_total', $state);
                                    })
                                    ->required(),

                                Section::make('Fechas de Dias a Compensar por Pago')
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
                                            ->dehydrated()
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

                                                    $set('total_days', $diasHabiles); // se guarda el resultado y le especificacmo en que campo queremos mostrarlo
                                                }
                                            }),
                                        DatePicker::make('end_date')
                                            ->label('Fecha Final')
                                            ->reactive()
                                            ->disabled(fn($get) => in_array($get('status'), [
                                                RequestStatus::Approved,
                                                RequestStatus::Rejected,
                                                RequestStatus::Pending,
                                                RequestStatus::ApprovedByManager
                                            ]))
                                            ->dehydrated()
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

                                                    $set('total_days', $diasHabiles);
                                                }
                                            }),
                                    ]),

                                TextInput::make('total_days')
                                    ->label('Dias Totales')
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager,
                                    ]))
                                    ->dehydrated()
                                    ->numeric()
                                    ->minValue('0')
                                    ->reactive()
                                    ->required()
                                    ->helperText('Dias habiles'),
                            ]),
                    ]),
                Grid::make(1)
                    ->schema([
                        Section::make('Informacion del Balance de Vacaciones')
                            ->columns(1)
                            ->schema([
                                TextInput::make('balance')
                                    ->reactive()
                                    ->readOnly()
                                    ->visibleOn('create', 'edit')
                                    ->label('Balance de Vacaciones Disponibles')
                                    ->placeholder('Vacaciones disponibles')
                                    ->default(
                                        fn($get) =>
                                        BalanceVacation::where('employee_id', $get('employee_id'))->value('balance') // Obtiene el balance de vacaciones del empleado seleccionado
                                    ),

                                Section::make('Balance de Compensacion por Pago')
                                    ->columns(1)
                                    ->schema([
                                        TextInput::make('paid_accrued')
                                            ->label('Acumulado')
                                            ->reactive()
                                            ->default(0),
                                        TextInput::make('used')
                                            ->label('Usado')
                                            ->default(0),
                                        TextInput::make('paid_total')
                                            ->label('Dias Totales para Compensar con Pago')
                                            ->reactive()
                                            ->default(0),
                                    ])
                            ]),
                        Section::make('Motivo por el cual Pide dias Pagados')
                            ->columns(1)
                            ->schema([
                                Textarea::make('comment')
                                    ->label('Descripcion')
                                    ->maxLength(255)
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager
                                    ]))
                                    ->dehydrated(),
                            ])

                    ]),
                //Se muestra un historico de lo que se hizo en esta solicitud
                Section::make('Historial de Cambios')
                    ->icon(Heroicon::Clock)
                    ->schema([
                        View::make('filament.components.request-logs')
                            ->viewData([
                                'record' => fn($livewire) => $livewire->getRecord(),
                            ]),
                    ])
                    ->visible(fn($livewire) => true/* $livewire->record !== null */) // solo en edit/view
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
