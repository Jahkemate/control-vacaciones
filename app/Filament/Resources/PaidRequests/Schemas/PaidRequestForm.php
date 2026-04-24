<?php

namespace App\Filament\Resources\PaidRequests\Schemas;

use App\Models\BalanceVacation;
use App\States\RequestStatus;
use Filament\Forms\Components\DatePicker;
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
                                TextInput::make('total_days')
                                    ->label('Dias Totales')
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager,
                                    ]))
                                    ->numeric()
                                    ->required(),
                                Select::make('status')
                                    ->disabled()
                                    ->reactive()
                                    ->label('Estado de la Solicitud')
                                    ->options(RequestStatus::class)
                                    ->default(RequestStatus::Draft),
                                DatePicker::make('request_date')
                                    ->label('Fecha de Creacion')
                                    ->readOnly()
                                    ->default(fn() => now()->format('Y-m-d'))
                                    ->disabled(fn($get) => in_array($get('status'), [
                                        RequestStatus::Approved,
                                        RequestStatus::Rejected,
                                        RequestStatus::Pending,
                                        RequestStatus::ApprovedByManager
                                    ]))
                                    ->required(),
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
                            ]),
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
