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
use Filament\Schemas\Schema;
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
                                    ->default(fn() => Auth::user()->employee?->first()?->id) // Establece el valor predeterminado al primer empleado del usuario autenticado
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
                                    ->required(),
                                Textarea::make('comment')
                                    ->label('Descripcion')
                                    ->maxLength(255),
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
            ]);
    }
}
