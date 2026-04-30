<?php


namespace App\Filament\Resources\VacationRequests\Pages;


use App\Filament\Resources\VacationRequests\VacationRequestResource;
use App\Models\BalanceVacation;
use App\Models\Employee;
use App\States\EmployeeStatus;
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


    //---------Botones en el formulario en el create------------
    protected function getHeaderActions(): array
    {
        return [
            Action::make('draft')
                ->label('Guardar como borrador')
                ->icon(Heroicon::DocumentText)
                ->requiresConfirmation()
                ->modalDescription('¿ Desea guardar como Borrador ?')
                ->modalSubmitActionLabel('Si, Guardar')
                ->color('save')
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['employee', 'admin', 'manager']))
                ->action(
                    fn() => $this->saveAs(RequestStatus::Draft),
                ),


            Action::make('pending')
                ->label('Enviar solicitud')
                ->icon(Heroicon::Inbox)
                ->requiresConfirmation()
                ->modalDescription('¿ Desea enviar esta Solicitud ?')
                ->modalSubmitActionLabel('Si, Enviar')
                ->modalIcon(Heroicon::OutlinedPaperAirplane)
                ->color('send')
                ->visible(fn() => Auth::user()?->hasAnyAppRole(['employee', 'admin', 'manager']))
                ->disabled()
                ->action(
                    fn() => $this->saveAs(RequestStatus::Pending),


                ),
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
        $data['employee_id'] = Auth::user()?->employee?->id;
        $this->record = static::getModel()::create($data);


        $this->redirect($this->getRedirectUrl());
    }
    //-------------------------------------------------------------




}
