<?php

namespace App\Filament\Resources\VacationRequests\Tables;

use App\Models\Employee;
use App\Models\User;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Auth;

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
                    ->label('Fecha Final')
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
                    ->tooltip(fn($record) => $record->comment) //muestra el texto completo al pasar el mouse
                    ->label('Comentario'),
                TextColumn::make('observation')
                    ->limit(10)
                    ->tooltip(fn($record) => $record->observation) //muestra el texto completo al pasar el mouse
                    ->label('Motivo de Rechazo')
                    ->dateTime()
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
                        $record->employee?->user_id === Auth::id() //Solo el empleado que creo la solicitud puede editar 
                    ),
                DeleteAction::make()
                    // Si la solicitud esta rechazada o aprobada o pendiente no la puede eliminar
                    ->disabled(fn($record) => $record
                        ->status === RequestStatus::Rejected ||
                        $record->status === RequestStatus::Approved ||
                        $record->status === RequestStatus::Pending)
                    ->visible(
                        fn($record) =>
                        $record->employee?->user_id === Auth::id() //Solo el empleado que creo la solicitud puede eliminar
                    ),
               /*  ViewAction::make()
                    // 
                    ->label('Ver Detalles')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->modalHeading('Detalles de la Solicitud')
                    ->modalWidth('md')
                    ->modalContent(fn($record) => view('filament.modals.vacation-request-details' , [
                        'request' => $record,
                        'employee' => $record->employee_id,
                        'user' => User::find(Employee::find($record->employee_id)->user_id)
                    ]))
                    ->visible(fn($record) => $record->status === RequestStatus::Approved || $record->status === RequestStatus::Rejected)
                    ->color('secondary')
                    ->infolist([])
                    ->modalSubmitAction(false), */
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
