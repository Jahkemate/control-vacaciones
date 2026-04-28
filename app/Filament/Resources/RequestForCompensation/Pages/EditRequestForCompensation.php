<?php

namespace App\Filament\Resources\RequestForCompensation\Pages;

use App\Filament\Resources\RequestForCompensation\RequestForCompensationResource;
use App\Mail\CompensationRequest\ApprovedCompensationRequest;
use App\Mail\CompensationRequest\ApprovedManagerCompensationRequest;
use App\Mail\CompensationRequest\PendingCompensationRequest;
use App\Mail\CompensationRequest\RejectedCompensationRequest;
use App\Models\User;
use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EditRequestForCompensation extends EditRecord
{
    protected static string $resource = RequestForCompensationResource::class;
    protected function getHeaderActions(): array
    {
        return [
            //------------------Boton de Aprobar----------------------------------------
            Action::make('approved')
                ->label('Aprobar Solicitud')
                ->icon(Heroicon::CheckBadge)
                ->requiresConfirmation()
                ->modalDescription('¿Desea aprobar esta solicitud?')
                ->modalSubmitActionLabel('Si, Aprobar')
                ->color('secondary')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager']) && //
                    ! in_array($this->record->status, [
                        RequestStatus::Approved,
                        RequestStatus::Rejected,
                        RequestStatus::Draft
                    ]))
                ->disabled(function () {
                    $user = Auth::user();

                    // Si es admin, solo puede aprobar cuando manager ya aprobó
                    if ($user->role === 'admin') {
                        return $this->record->status !== RequestStatus::ApprovedByManager;
                    }

                    // Manager puede aprobar cuando está pendiente
                    if ($user->role === 'manager') {
                        return $this->record->status !== RequestStatus::Pending;
                    }

                    return true;
                })
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

                    // Si la solicitud se aprueba completamente, se establece la fecha de aprobación
                    if ($this->record->status === RequestStatus::Approved) {
                        $this->record->approval_date = now();
                    }

                    $this->saveAs($this->record->status);
                    $employeeUser = $this->record->employee?->user;

                    if ($employeeUser) {
                        Notification::make()
                            ->title('Solicitud aprobada')
                            ->success()
                            ->body(match ($this->record->status) {
                                RequestStatus::ApprovedByManager => 'Solicitud de Vacaciones Aprobada por jefe, esperando RRHH.',
                                RequestStatus::ApprovedByRRHH => 'Solicitud de Vacaciones Aprobada por RRHH.',
                                default => ''
                            })
                            ->sendToDatabase([$user, $employeeUser]);
                    }

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
                ->icon(Heroicon::XCircle)
                ->requiresConfirmation()
                ->schema([
                    Textarea::make('additional_comment')
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
                ->action(function (array $data, $record) {
                    $this->saveAs(RequestStatus::Rejected);
                    $record->commentsAdditional()->create([
                        'user_id' => Auth::id(),
                        'additional_comment' => $data['additional_comment'],
                        'type_comment' => 'rejection',
                    ]);
                }),
            //--------------------------------------------------------------------------

            //--------------------Boton de Guardar como Borrador------------------------
            Action::make('draft')
                ->label('Guardar como Borrador')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea guardar como Borrador ?')
                ->modalSubmitActionLabel('Si, Guardar')
                ->modalIcon(Heroicon::OutlinedPencil)
                ->color('save')
                ->icon(Heroicon::DocumentText)
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager', 'employee']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::Approved,
                        RequestStatus::ApprovedByManager,
                        RequestStatus::ApprovedByRRHH,
                        RequestStatus::Draft,
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
                ->icon(Heroicon::Inbox)
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
                ->action(function () {
                    $this->saveAs(RequestStatus::Pending);
                }),
            //---------------------------------------------------------------------------

            //--------------------Boton de Imprimir Solicitud----------------------------------------
            Action::make('print')
                ->label('Imprimir Solicitud')
                ->color('primary')
                ->icon(Heroicon::Printer)
                ->visible(fn() => in_array(Auth::user()?->role, ['manager', 'employee', 'admin']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::ApprovedByManager
                    ]))
                ->url(fn($record) => route('print.compensation', [
                    'id' => $record->id
                ]))
                ->openUrlInNewTab(),
            //--------------------Fin Boton de Imprimir Solicitud----------------------------------------
            //--------------------Boton de cancelar solicitud--------------------------------------------
            Action::make('cancel')
                ->label('Cancelar')
                ->icon(Heroicon::ArrowUturnLeft)
                ->url($this->getResource()::getUrl('index')) // redirige al listado
                ->color('gray'),
        ];
    }
    protected function getFormActions(): array
    {
        return [];
    }

    //Guarda el estado de la solicitud
    protected function saveAs(RequestStatus $status, $additional_comment = null)
    {
        $oldStatus = $this->record->getOriginal('status');
       
        // Se asignan valores antes de guardarse
        $this->record->status = $status;

        // Logica para el calculo del balance de compensacion
        if ($status === RequestStatus::Approved) {

            $accrued = $this->record->accrued_compensation ?? 0;

            $used = $this->record->total_days ?? 0;

            $this->record->used = $used;

            $this->record->total_compensation = $accrued - $used;


            $this->record->approval_date = now();
        }


        // Se guarda todo
        $this->record->save();


        // SOLO si cambió el estado, se manda correo a los diferentes destinatarios
        if ($oldStatus !== $status) {

            $email = $this->record->employee?->user?->email;

            if (!$email) {
                logger('No email found for employee user');
                return;
            }
            //Envia correo al jefe del departamento
            if ($status === RequestStatus::Pending) {

                $manager = User::where('role', 'manager')?->first();

                if ($manager?->email) {
                    Mail::to($manager->email)
                        ->send(new PendingCompensationRequest($this->record, Auth::user()));
                }

                Notification::make()
                    ->title('Solicitud Pendiente')
                    ->body('Tienes una Solicitud por Compensacion Pendiente')
                    ->iconColor('primary')
                    ->icon(Heroicon::OutlinedDocument)
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('primary')
                            ->url(RequestForCompensationResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($manager);
            }

            if ($status === RequestStatus::Approved) {

                $employee = $this->record->employee?->user;

                if ($employee?->email) {
                    Mail::to($employee->email)
                        ->send(new ApprovedCompensationRequest($this->record, Auth::user()));
                }

                Notification::make()
                    ->title('Solicitud Aprobada')
                    ->body('Tu Solicitud por Compensación fue aprobada')
                    ->iconColor('success')
                    ->icon('heroicon-o-check-circle')
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('success')
                            ->url(RequestForCompensationResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($employee);
            }

            if ($status === RequestStatus::ApprovedByManager) {

                $admins = User::where('role', 'admin')->get();

                foreach ($admins as $admin) {
                    Mail::to($admin?->email)
                        ->send(new ApprovedManagerCompensationRequest($this->record, Auth::user()));
                }
                Notification::make()
                    ->title('Solicitud aprobada por Jefe')
                    ->body('Solicitud por Compensación Aprobada por Jefe')
                    ->iconColor('send')
                    ->icon(Heroicon::OutlinedDocumentCheck)
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('send')
                            ->url(RequestForCompensationResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($admins);
            }

            if ($status === RequestStatus::Rejected) {
                $rejectedUser = $this->record->employee?->user;

                if ($rejectedUser) {
                    Mail::to($rejectedUser->email)
                        ->send(new RejectedCompensationRequest($this->record, Auth::user()));
                }

                Notification::make()
                    ->title('Solicitud Rechazada')
                    ->body('Tu Solicitud por Compensacón fue Rechazada') 
                    ->iconColor('danger')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('danger')
                            ->url(RequestForCompensationResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($rejectedUser);
            }
        }
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
                // Si es borrador, no hace nada
                break;

            // Si es Rechazada se mostrara una notificacion
            case RequestStatus::Rejected:
                Notification::make()
                    ->title('Esta solicitud ha sido rechazada')
                    ->body('Las solicitudes rechazadas no pueden ser editadas.')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->send();
                //$this->redirect($this->getRedirectUrl());
                break;

            case RequestStatus::Approved: // Aprobada
                Notification::make()
                    ->title('Esta solicitud ya fue aprobada')
                    ->body('Las solicitudes aprobadas no pueden ser editadas.')
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->send();
                //$this->redirect($this->getRedirectUrl());
                break;

            default:
                // Para cualquier otro estado que no sea borrador
                Notification::make()
                    ->title('No puedes editar una solicitud Enviada')
                    ->body('Solo las solicitudes en estado de borrador pueden ser editadas.')
                    ->color('send')
                    ->icon(Heroicon::OutlinedExclamationCircle)
                    ->send();
                //$this->redirect($this->getRedirectUrl());
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
