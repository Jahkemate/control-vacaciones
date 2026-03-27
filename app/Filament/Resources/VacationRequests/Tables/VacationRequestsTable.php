<?php

namespace App\Filament\Resources\VacationRequests\Tables;

use App\Models\User;
use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Foundation\Auth\User as AuthUser;

class VacationRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->label('Empleado Solicitante'),
                TextColumn::make('start_date')
                    ->searchable()
                    ->label('Fecha de Inicio')
                    ->date(),
                TextColumn::make('end_date')
                    ->searchable()
                    ->label('Fecha de Inicio')
                    ->date(),
                TextColumn::make('total_business_days')
                    ->alignCenter()
                    ->searchable()
                    ->label('Dias Totales'),
                TextColumn::make('created_at')
                    ->label('Fecha de Solicitud')
                    ->searchable()
                    ->dateTime(),
                TextColumn::make('status')
                    ->alignCenter()
                    ->badge()
                    ->searchable()
                    ->label('Estado de la Solicitud')
                    ->formatStateUsing(fn(RequestStatus $state) => $state->getLabel())
                    ->color(fn(RequestStatus $state) => $state->getColor())
                    ->icon(fn(RequestStatus $state) => $state->getIcon()),
                TextColumn::make('comment')
                    ->searchable()
                    ->limit(10)
                    ->tooltip(fn($record) => $record->comment) //muestra el texto complteo al pasar el mouse
                    ->label('Comentario'),
                TextColumn::make('observation')
                    ->limit(10)
                    ->tooltip(fn($record) => $record->comment) //muestra el texto complteo al pasar el mouse
                    ->label('Motivo de Rechazo')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->label('Filtrar por Estado')
                    ->options(RequestStatus::class),
                AuthUser::pluck('department_id', 'name')->toArray(), //para facilitar la busquea mas eficiente en la base de datos.
            ])
            ->recordActions([
                Action::make('viewComments')
                    ->color(fn($record) =>
                    $record->comment ? 'success' : 'gray') // hace el color dinamico
                    ->label('Ver Comentario')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Comentarios')
                    ->modalWidth('sm') // cambia el tamaño del modal
                    ->modalContent(fn($record) => view('filament.modals.comments', [
                        'comments' => $record->comment
                    ]))
                    ->modalSubmitAction(false),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
