<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class EditVacationRequest extends EditRecord
{
    protected static string $resource = VacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //------------------Boton de Aprobar----------------------------------------
            Action::make('approved')
                ->label('Aprobar Solicitud')
                ->requiresConfirmation()
                ->modalDescription('¿Desea aprobar esta solicitud?')
                ->modalSubmitActionLabel('Si, Aprobar')
                ->color('secondary')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Approved,
                        RequestStatus::Rejected,
                        RequestStatus::Draft
                    ]))
                ->disabled(fn() =>
                in_array($this->record->status, [
                    RequestStatus::Approved,
                    RequestStatus::Rejected,
                ]))

                ->action(function () {
                    $user = Auth::user();
                    $currentStatus = $this->record->status;

                    // Jefe aprueba
                    if ($user->role === 'manager') {
                        if ($currentStatus === RequestStatus::ApprovedByRRHH) {
                            $this->record->status = RequestStatus::Approved; // ambos aprobaron
                        } elseif ($currentStatus === RequestStatus::Pending) {
                            $this->record->status = RequestStatus::ApprovedByManager; // solo jefe aprobó
                        }
                    }

                    // Admin aprueba
                    if ($user->role === 'admin') {
                        if ($currentStatus === RequestStatus::ApprovedByManager) {
                            $this->record->status = RequestStatus::Approved; // ambos aprobaron
                        } elseif ($currentStatus === RequestStatus::Pending) {
                            $this->record->status = RequestStatus::ApprovedByRRHH; // solo admin aprobó
                        }
                    }

                    $this->record->save();

                    Notification::make()
                        ->title('Solicitud aprobada')
                        ->success()
                        ->body(match ($this->record->status) {
                            RequestStatus::Approved => 'La solicitud ha sido aprobada por ambos.',
                            RequestStatus::ApprovedByManager => 'Aprobada por jefe, esperando admin.',
                            RequestStatus::ApprovedByRRHH => 'Aprobada por RRHH, esperando jefe.',
                            default => ''
                        })
                        ->send();

                    $this->redirect($this->getRedirectUrl());
                }),
            //------------------------------------------------------------------------

            //------------------Boton de Rechazar-------------------------------------
            Action::make('rejected')
                ->label('Rechazar Solicitud')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea rechazar esta Solicitud ?')
                ->modalSubmitActionLabel('Si, Rechazar')
                ->color('danger')
                ->requiresConfirmation()
                ->schema([
                    Textarea::make('observation')
                        ->label('Comentario')
                        ->required(),
                ])
                ->modalSubmitActionLabel('Rechazar')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Approved,
                        RequestStatus::Rejected,
                        //RequestStatus::ApprovedByManager,
                        RequestStatus::Draft
                    ]))
                ->disabled(fn() =>
                in_array($this->record->status, [
                    RequestStatus::Approved,
                    RequestStatus::Rejected,
                    //RequestStatus::ApprovedByManager,
                ]))
                ->action(function (array $data) {
                    $this->saveAs(RequestStatus::Rejected, $data['observation']);
                }),
            //--------------------------------------------------------------------------

            //--------------------Boton de Guardar como Borrador------------------------
            Action::make('draft')
                ->label('Guardar como Borrador')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea guardar como Borrador ?')
                ->modalSubmitActionLabel('Si, Guardar')
                ->modalIcon(Heroicon::OutlinedPencil)
                ->color('gray')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager', 'employee']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::Approved,
                        RequestStatus::ApprovedByManager,
                        RequestStatus::ApprovedByRRHH,
                    ]))
                ->disabled(fn() =>
                in_array($this->record->status, [
                    RequestStatus::Approved,
                    RequestStatus::Rejected,
                    RequestStatus::ApprovedByManager,
                    RequestStatus::ApprovedByRRHH,
                ]))
                ->action(fn() => $this->saveAs(RequestStatus::Draft)),
            //---------------------------------------------------------------------------

            //--------------------Boton de Enviar----------------------------------------
            Action::make('pending')
                ->label('Enviar Solicitud')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea enviar esta Solicitud ?')
                ->modalSubmitActionLabel('Si, Enviar')
                ->modalIcon(Heroicon::OutlinedPaperAirplane)
                ->color('send')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager', 'employee']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::Approved,
                        RequestStatus::ApprovedByManager,
                        RequestStatus::ApprovedByRRHH,
                    ]))
                ->disabled(fn() =>
                in_array($this->record->status, [
                    RequestStatus::Approved,
                    RequestStatus::Rejected,
                ]))
                ->action(fn() => $this->saveAs(RequestStatus::Pending)),
            //---------------------------------------------------------------------------
        ];
    }

    //Garda el estado de la solicitud
    protected function saveAs(RequestStatus $status, $observation = null)
    {
        $this->save(); // guarda cambios del form

        $this->record->update([
            'status' => $status,
            'comment' => $observation,
        ]);

        $this->redirect($this->getRedirectUrl());
    }
    //------------------------------------------------------

    // Esto es para evitar que se puedea editar una solicitud en estado diferente a borrador
    protected function beforeFill(): void
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'manager'])) {
            if ($this->record->status === RequestStatus::Pending || $this->record->status === RequestStatus::ApprovedByManager) {
                return;
            }
        };
        // Comprobamos el estado de la solicitud
        switch ($this->record->status) {
            case RequestStatus::Draft:
                // Si es borrador, no hacemos nada
                break;

            case RequestStatus::Rejected: // Rechazada
                Notification::make()
                    ->title('Esta solicitud ha sido rechazada')
                    ->body('Las solicitudes rechazadas no pueden ser editadas.')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->send();
                $this->redirect($this->getRedirectUrl());
                break;

            case RequestStatus::Approved: // Aprobada
                Notification::make()
                    ->title('Esta solicitud ya fue aprobada')
                    ->body('Las solicitudes aprobadas no pueden ser editadas.')
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->send();
                $this->redirect($this->getRedirectUrl());
                break;

            default:
                // Para cualquier otro estado que no sea borrador
                Notification::make()
                    ->title('No puedes editar una solicitud Enviada')
                    ->body('Solo las solicitudes en estado de borrador pueden ser editadas.')
                    ->color('send')
                    ->icon(Heroicon::OutlinedExclamationCircle)

                    ->send();
                $this->redirect($this->getRedirectUrl());
                break;
        }
    }

    //-----------------------------------------------------------------
    protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    }
}
