<?php

namespace App\Filament\Resources\VacationRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VacationRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('employee_id')
                    ->label('Empleado Solicitante'),
                TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Fecha de Inicio')
                    ->date(),
                TextColumn::make('state')
                    ->label('Estado de la Solicitud'),
                TextColumn::make('request_date')
                    ->label('Fecha de Creacion')
                    ->dateTime(),
                TextColumn::make('comment')
                    ->label('Comentario'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
