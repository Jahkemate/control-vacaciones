<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use App\Models\Employee;
use App\States\RequestStatus;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class CreateVacationRequest extends CreateRecord
{
    protected static string $resource = VacationRequestResource::class;

    public ?RequestStatus $currentAction = null;

    /* protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    } */


    //---------Botones en el formulario en el create------------
    protected function getHeaderActions(): array
    {
        return [
            Action::make('draft')
                ->label('Guardar como borrador')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea guardar como Borrador ?')
                ->modalSubmitActionLabel('Si, Guardar')
                ->color('save')
                ->visible(fn() => in_array(Auth::user()?->role, ['employee', 'admin', 'manager']))
                ->action(
                    fn() => $this->saveAs(RequestStatus::Draft),
                ),

            Action::make('pending')
                ->label('Enviar solicitud')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea enviar esta Solicitud ?')
                ->modalSubmitActionLabel('Si, Enviar')
                ->modalIcon(Heroicon::OutlinedPaperAirplane)
                ->color('send')
                ->visible(fn() => in_array(Auth::user()?->role, ['employee', 'admin', 'manager']))
                ->action(
                    fn() => $this->saveAs(RequestStatus::Pending),

                ),
            //--------------------Boton de cancelar solicitud--------------------------------------------
            Action::make('cancel')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl('index')) // redirige al listado
                ->color('gray'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    //Guarda el estado de la solicitud
    protected function saveAs(RequestStatus $state)
    {
        // Solo valida cuando es enviar
        if ($state === RequestStatus::Pending) {
            // Solo validar cuando es enviar
            $this->form->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'total_business_days' => 'required|integer|min:1',
            ]);
        }


        $data = $this->form->getState();

        $data['status'] = $state;
        $data['employee_id'] = Auth::user()->employee?->first()?->id;
        $this->record = static::getModel()::create($data);

        $this->redirect($this->getRedirectUrl());
    }
    //-------------------------------------------------------------


}
