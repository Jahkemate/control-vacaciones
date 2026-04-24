<?php

namespace App\Filament\Resources\RequestForCompensation\Schemas;

use App\States\RequestStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class RequestForCompensationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informacion de la Solicitud')
                    ->columns(1)
                    ->schema([
                        Select::make('employee_id')
                            ->label('Empleado Solicitante')
                            ->default(fn() => Auth::user()->employee?->id) // Establece el valor predeterminado al primer empleado del usuario autenticado
                            ->disabled()
                            ->dehydrated()
                            ->relationship('employee', 'first_name') //Para que solo me muestre los empleadfos que tiene balance
                            ->getOptionLabelFromRecordUsing(
                                fn($record) =>
                                $record->first_name . ' ' . $record->last_name
                            )
                            ->reactive(),
                        DateTimePicker::make('date_creation')
                            ->label('Fecha de Creacion')
                            ->readOnly()
                            ->default(now())
                            ->required(),
                        TextInput::make('total_days')
                            ->label('Dias Totales')
                            ->disabled(fn($get) => in_array($get('status'), [
                                RequestStatus::Approved,
                                RequestStatus::Rejected,
                                RequestStatus::Pending,
                                RequestStatus::ApprovedByManager
                            ]))
                            ->numeric()
                            ->required(),
                        Select::make('status')
                            ->disabled()
                            ->reactive()
                            ->label('Estado de la Solicitud')
                            ->options(RequestStatus::class)
                            ->default(RequestStatus::Draft)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state === RequestStatus::Approved) {
                                    $set('approval_date', now());
                                }
                            }),
                        DatePicker::make('approval_date')
                            ->label('Fecha de Aprobacion')
                            ->disabled(),
                        //->required(),
                        DatePicker::make('pending_date')
                            ->label('Fecha de Pendiente')
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
