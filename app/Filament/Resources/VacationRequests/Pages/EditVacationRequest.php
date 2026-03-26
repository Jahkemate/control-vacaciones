<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
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
                ->action(fn() => $this->saveAs(RequestStatus::Approved)),
            //------------------------------------------------------------------------

            //------------------Boton de Rechazar-------------------------------------
            Action::make('rejected')
                ->label('Rechazar Solicitud')
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
                        RequestStatus::Draft
                    ]))
                ->disabled(fn() =>
                in_array($this->record->status, [
                    RequestStatus::Approved,
                    RequestStatus::Rejected,
                ]))
                ->action(function (array $data) {
                    $this->saveAs(RequestStatus::Rejected, $data['observation']);
                }),
            //--------------------------------------------------------------------------

            //--------------------Boton de Guardar como Borrador------------------------
            Action::make('draft')
                ->label('Guardar como Borrador')
                ->color('gray')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager', 'employee']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::Approved
                    ]))
                ->disabled(fn() =>
                in_array($this->record->status, [
                    RequestStatus::Approved,
                    RequestStatus::Rejected,
                ]))
                ->action(fn() => $this->saveAs(RequestStatus::Draft)),
            //---------------------------------------------------------------------------
            
            //--------------------Boton de Enviar----------------------------------------
            Action::make('pending')
                ->label('Enviar Solicitud')
                ->color('send')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager', 'employee']) &&
                    ! in_array($this->record->status, [
                        RequestStatus::Pending,
                        RequestStatus::Rejected,
                        RequestStatus::Approved
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

    //Para que el formulario este desactivo de acuerdo a los siguientes estados
    protected function isFormDisabled(): bool
    {
        return in_array($this->record->status, [
            RequestStatus::Approved,
            RequestStatus::Rejected,
            RequestStatus::Pending
        ]);
    }
    //-----------------------------------------------------------------
    protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    }
}
