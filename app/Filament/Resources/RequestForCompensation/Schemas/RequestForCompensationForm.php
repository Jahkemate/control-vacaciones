<?php

namespace App\Filament\Resources\RequestForCompensation\Schemas;

use App\States\RequestStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
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
                            ->default(fn() => Auth::user()->employee?->first()?->id) // Establece el valor predeterminado al primer empleado del usuario autenticado
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
                            ->default(now()),
                        TextInput::make('total_days')
                            ->label('Dias Totales')
                            ->numeric()
                            ->required(),
                        Select::make('status')
                            ->disabled()
                            ->reactive()
                            ->label('Estado de la Solicitud')
                            ->options(RequestStatus::class)
                            ->default(RequestStatus::Draft),
                        DatePicker::make('approval_date')
                            ->label('Fecha de Aprobacion')
                            ->required(),
                        DatePicker::make('pending_date')
                            ->label('Fecha de Pendiente')
                            ->required(),
                        Textarea::make('comment')
                            ->label('Descripcion')
                            ->maxLength(255),
                    ])

            ]);
    }
}
