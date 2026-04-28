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
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
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
                TextColumn::make('status')
                    ->alignCenter()
                    ->badge()
                    ->searchable()
                    ->label('Estado de la Solicitud')
                    ->formatStateUsing(fn(RequestStatus $state) => $state->getLabel())
                    ->color(fn(RequestStatus $state) => $state->getColor())
                    ->icon(fn(RequestStatus $state) => $state->getIcon()),
                TextColumn::make('request_date')
                    ->label('Fecha de Creacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Fecha Final')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_days')
                    ->label('Dias Totales')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('paid_accrued')
                    ->label('Total Acumulado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('used')
                    ->label('Dias Usados')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('paid_total')
                    ->label('Dias Totales Pagados')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Fecha de Aprobacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('comment')
                    ->searchable()
                    ->limit(10)
                    ->tooltip(fn($record) => $record->comment)
                    ->label('Descripcion'),
                TextColumn::make('rejection_comment')
                    ->label('Motivo de Rechazo')
                    ->limit(20)
                    ->tooltip(
                        fn($record) =>
                        $record->commentsAdditional()
                            ->where('type_comment', 'rejection')
                            ->latest()
                            ->value('additional_comment')
                    )
                    ->getStateUsing(
                        fn($record) =>
                        $record->commentsAdditional()
                            ->where('type_comment', 'rejection')
                            ->latest()
                            ->value('additional_comment')
                    )
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function ($query) {
                $user = Auth::user();
                $employee = $user->employee;

                $employeeId = $employee?->id;
                $departmentId = $employee?->department_id;

                // ADMIN → todo menos drafts de otros 
                if ($user->role === 'admin') {
                    return $query
                        ->where(function ($q) use ($employeeId) {

                            // 1. Todo lo que NO es draft
                            $q->where('status', '!=', RequestStatus::Draft);

                            // 2. Sus propios drafts (si tiene employee)
                            if ($employeeId) {
                                $q->orWhere(function ($sub) use ($employeeId) {
                                    $sub->where('employee_id', $employeeId)
                                        ->where('status', RequestStatus::Draft);
                                });
                            }
                        })
                        ->orderBy('created_at', 'desc');
                }

                //  MANAGER → su departamento + lo suyo
                if ($user->role === 'manager' && $employee) {
                    return $query
                        ->where(function ($q) use ($employeeId, $departmentId) {

                            // 1. Sus propias solicitudes (incluye drafts)
                            if ($employeeId) {
                                $q->where('employee_id', $employeeId);
                            }

                            // 2. Departamento (sin drafts)
                            if ($departmentId) {
                                $q->orWhere(function ($sub) use ($departmentId) {
                                    $sub->whereHas('employee', function ($emp) use ($departmentId) {
                                        $emp->where('department_id', $departmentId);
                                    })
                                        ->where('status', '!=', RequestStatus::Draft);
                                });
                            }
                        })
                        ->orderBy('created_at', 'desc');
                }

                // EMPLEADO → solo lo suyo
                if ($employeeId) {
                    return $query
                        ->where('employee_id', $employeeId)
                        ->orderBy('created_at', 'desc');
                }

                // fallback 
                return $query->whereRaw('1 = 0');
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
                ViewAction::make()
                    // 
                    ->label('Ver Detalles')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->modalHeading('Detalles de la Solicitud')
                    ->modalWidth('2xl')
                    ->modalContent(fn($record) => view('filament.modals.vacation-request-details', [
                        'request' => $record,
                        'employee' => $record->employee,
                        'user' => $record->employee?->user,
                    ]))
                    ->visible(fn($record) => $record->status === RequestStatus::Rejected) //
                    ->color('secondary')
                    ->infolist([])
                    ->modalSubmitAction(false)
                    ->modalSubmitActionLabel('Cerrar'),
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
