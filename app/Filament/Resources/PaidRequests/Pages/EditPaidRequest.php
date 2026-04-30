<?php

namespace App\Filament\Resources\PaidRequests\Pages;

use App\Filament\Resources\PaidRequests\PaidRequestResource;
use App\Mail\PaidRequest\ApprovedManagerPaidRequest;
use App\Mail\PaidRequest\ApprovedPaidRequest;
use App\Mail\PaidRequest\PendingPaidRequest;
use App\Mail\PaidRequest\RejectedPaidRequest;
use App\Models\User;
use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EditPaidRequest extends EditRecord
{
    protected static string $resource = PaidRequestResource::class;
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
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['admin', 'manager']) && //
                    ! in_array($this->record->status, [
                        RequestStatus::Approved,
                        RequestStatus::Rejected,
                        RequestStatus::Draft
                    ]))
                ->disabled(function () {
                    $user = Auth::user();


                    // Si es admin, solo puede aprobar cuando manager ya aprobó
                    if ($user->hasRole('admin')) {
                        return $this->record->status !== RequestStatus::ApprovedByManager;
                    }


                    // Manager puede aprobar cuando está pendiente
                    if ($user->hasRole('manager')) {
                        return $this->record->status !== RequestStatus::Pending;
                    }


                    return true;
                })
                ->action(function () {
                    $user = Auth::user();
                    $currentStatus = $this->record->status;


                    // Jefe aprueba
                    if ($user->hasRole('manager')) {
                        if ($currentStatus === RequestStatus::ApprovedByRRHH) {
                            $this->record->status = RequestStatus::Approved; // ambos aprobaron
                        } elseif ($currentStatus === RequestStatus::Pending) {
                            $this->record->status = RequestStatus::ApprovedByManager; // solo jefe aprobó
                        }
                    }


                    // Admin aprueba
                    if ($user->hasRole('admin')) {
                        if ($currentStatus === RequestStatus::ApprovedByManager) {
                            $this->record->status = RequestStatus::Approved; // ambos aprobaron
                        } elseif ($currentStatus === RequestStatus::Pending) {
                            $this->record->status = RequestStatus::ApprovedByRRHH; // solo admin aprobó
                        }
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
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['admin', 'manager']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Approved,
                        RequestStatus::Rejected,
                        RequestStatus::Draft
                    ]))
                ->disabled(function () {
                    $user = Auth::user();


                    // Si es admin, solo puede aprobar cuando manager ya aprobó
                    if ($user->hasRole('admin')) {
                        return $this->record->status !== RequestStatus::ApprovedByManager;
                    }


                    // Manager puede aprobar cuando está pendiente
                    if ($user->hasRole('manager')) {
                        return $this->record->status !== RequestStatus::Pending;
                    }


                    return true;
                })
                ->action(function (array $data, $record) {
                    $this->saveAs(RequestStatus::Rejected);
                    $record->commentsAdditional()->create([
                        'user_id' => Auth::id(),
                        'additional_comment' => $data['additional_comment'],
                        'type_comment' => 'rejection',
                    ]);


                    $this->redirect($this->getRedirectUrl());
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
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['admin', 'manager', 'employee']) &&
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
                ->action(fn() => $this->saveDraft(RequestStatus::Draft)),
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
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['admin', 'manager', 'employee']) &&
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
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['admin', 'manager', 'employee']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::ApprovedByManager
                    ]))
                ->url(fn($record) => URL::signedRoute('print.paid', [
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


    //----------------------Funcion pero solo para guardar como borrador--------------------------
    protected function saveDraft(RequestStatus $status): void
    {
        $data = $this->form->getState();


        $data['status'] = $status;


        $this->record->update($data);
    }
    //Garda el estado de la solicitud
    protected function saveAs(RequestStatus $status, $additional_comment = null)
    {
        $oldStatus = $this->record->getOriginal('status');
        // Se asignan valores antes de guardarse
        $this->record->status = $status;


        // Logica para el calculo del balance de compensacion
        if ($status === RequestStatus::Approved) {


            $accrued = $this->record->paid_accrued ?? 0;


            $used = $this->record->total_days ?? 0;


            $this->record->used = $used;


            $this->record->paid_total = $accrued - $used;


            $this->record->request_date = now();
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


                $manager = User::role('manager')?->first();


                if ($manager?->email) {
                    Mail::to($manager->email)
                        ->send(new PendingPaidRequest($this->record, Auth::user()));
                }


                Notification::make()
                    ->title('Solicitud Pendiente')
                    ->body('Tienes una Solicitud de Pago Pendiente')
                    ->iconColor('primary')
                    ->icon(Heroicon::OutlinedDocument)
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('primary')
                            ->url(PaidRequestResource::getUrl('edit', [
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
                        ->send(new ApprovedPaidRequest($this->record, Auth::user()));
                }


                Notification::make()
                    ->title('Solicitud de Pago Aprobada')
                    ->body('Tu Solicitud de Pago fue aprobada')
                    ->iconColor('success')
                    ->icon('heroicon-o-check-circle')
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('success')
                            ->url(PaidRequestResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($employee);
            }


            if ($status === RequestStatus::ApprovedByManager) {


                $admins = User::role('admin')->get();


                foreach ($admins as $admin) {
                    Mail::to($admin?->email)
                        ->send(new ApprovedManagerPaidRequest($this->record, Auth::user()));
                }


                Notification::make()
                    ->title('Solicitud aprobada por Jefe')
                    ->body('Solicitud de Pago Aprobada por Jefe')
                    ->iconColor('send')
                    ->icon(Heroicon::OutlinedDocumentCheck)
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('send')
                            ->url(PaidRequestResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($admins);
            }


            if ($status === RequestStatus::Rejected) {
                $rejected = $this->record->employee?->user;


                if ($rejected?->email) {
                    Mail::to($rejected->email)
                        ->send(new RejectedPaidRequest($this->record, Auth::user()));
                }


                Notification::make()
                    ->title('Solicitud Rechazada')
                    ->body('Tu Solicitud de Pago fue Rechazada')
                    ->iconColor('danger')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->actions([
                        Action::make('view')
                            ->label('Ver Solicitud')
                            ->color('danger')
                            ->url(PaidRequestResource::getUrl('edit', [
                                'record' => $this->record->id,
                            ]))
                            ->button(),
                    ])
                    ->sendToDatabase($rejected);
            }
        }
    }
    //------------------------------------------------------


    // Esto es para evitar que se puedea editar una solicitud en estado diferente a borrador
    protected function beforeFill(): void
    {
        $user = Auth::user();


        // admins y managers pueden editar estados específicos
        if ($user->hasAnyRole(['admin', 'manager'])) {
            if (
                $this->record->status === RequestStatus::Pending ||
                $this->record->status === RequestStatus::ApprovedByManager
            ) {
                return;
            }
        }


        //  bloquear edición si NO es borrador
        if ($this->record->status !== RequestStatus::Draft) {


            match ($this->record->status) {
                RequestStatus::Rejected => Notification::make()
                    ->title('Esta solicitud ha sido rechazada')
                    ->body('Las solicitudes rechazadas no pueden ser editadas.')
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->send(),


                RequestStatus::Approved => Notification::make()
                    ->title('Esta solicitud ya fue aprobada')
                    ->body('Las solicitudes aprobadas no pueden ser editadas.')
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->send(),


                default => Notification::make()
                    ->title('No puedes editar esta solicitud')
                    ->body('Solo las solicitudes en estado de borrador pueden ser editadas.')
                    ->color('warning')
                    ->icon(Heroicon::OutlinedExclamationCircle)
                    ->send(),
            };

        }
    }


    //-----------------------------------------------------------------
    protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    }
    //-----------------------------------------------------------------


}
