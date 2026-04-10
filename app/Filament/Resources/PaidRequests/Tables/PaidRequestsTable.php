<?php

namespace App\Filament\Resources\PaidRequests\Tables;

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
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaidRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->label('Empleado Solicitante'),
                TextColumn::make('total_days')
                    ->numeric()
                    ->sortable(),
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
                    ->tooltip(fn($record) => $record->comment)
                    ->label('Descripcion'),
                TextColumn::make('request_date')
                    ->label('Fecha de Creacion')
                    ->date()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function ($query) {
                $user = Auth::user();

                // Empleado actual (registro en employees)
                $employee = $user->employee;

                // Admin → ve todo
                if ($user->role === 'admin') {
                    return $query
                        ->where(function ($q) use ($employee) {
                            $q->where('status', '!=', RequestStatus::Draft) // todas las solicitudes enviadas
                                ->orWhere('employee_id', $employee?->first()?->id); // sus borradores
                        })
                        ->orderBy('created_at', 'desc');
                }
                // Jefe → ve su departamento
                if ($user->role === 'manager' && $employee) {
                    return $query
                        ->where(function ($q) use ($employee) {

                            // 1. Jefe puede ver sus propias solicitudes (incluye borradores)
                            $q->where('employee_id', $employee->first()->id)

                                // 2. Puede ver solicitudes del departamento SIN borradores
                                ->orWhere(function ($sub) use ($employee) {
                                    $sub->whereHas('employee', function ($emp) use ($employee) {
                                        $emp->where('department_id', $employee->first()->department_id);
                                    })
                                        ->where('status', '!=', RequestStatus::Draft);
                                });
                        })
                        ->orderBy('created_at', 'desc');
                }

                // Empleado normal → solo sus solicitudes
                return $query
                    ->where('employee_id', $employee?->first()?->id)
                    ->orderBy('created_at', 'desc');
            })
            ->filters([
                SelectFilter::make('status')
                    ->label('Filtrar por Estado')
                    ->options(RequestStatus::class),
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
                EditAction::make()
                    // Si la solicitud esta rechazada o aprobada o pendiente no la puede editar
                    ->disabled(fn($record) => $record
                        ->status === RequestStatus::Rejected ||
                        $record->status === RequestStatus::Approved ||
                        $record->status === RequestStatus::Pending)
                    ->visible(
                        fn($record) =>
                        $record->employee?->user_id === Auth::id() &&  //Solo el empleado que creo la solicitud puede editar 
                            // Solo si la solicitud no esta en estado Rechazada o Aprobada o Pendiente
                            ! in_array($record->status, [
                                RequestStatus::Rejected,
                                RequestStatus::Approved,
                                RequestStatus::Pending,
                            ])
                    ),
                DeleteAction::make()
                    // Si la solicitud esta rechazada o aprobada o pendiente no la puede eliminar
                    ->disabled(fn($record) => $record
                        ->status === RequestStatus::Rejected ||
                        $record->status === RequestStatus::Approved ||
                        $record->status === RequestStatus::Pending)
                    ->visible(
                        fn($record) =>
                        $record->employee?->user_id === Auth::id() && //Solo el empleado que creo la solicitud puede eliminar
                            ! in_array($record->status, [
                                RequestStatus::Rejected,
                                RequestStatus::Approved,
                                RequestStatus::Pending,
                            ])
                    ),
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
