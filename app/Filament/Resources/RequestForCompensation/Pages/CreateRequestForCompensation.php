<?php


namespace App\Filament\Resources\RequestForCompensation\Pages;


use App\Filament\Resources\RequestForCompensation\RequestForCompensationResource;
use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;


class CreateRequestForCompensation extends CreateRecord
{
    protected static string $resource = RequestForCompensationResource::class;


     public ?RequestStatus $currentAction = null;


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
                ->disabled()
                ->color('send')
                 ->visible(fn() => Auth::user()?->hasAnyAppRole(['employee', 'admin', 'manager']))
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
                'total_days' => 'required|date',
                'request_date' => 'required|date',
            ]);
        }




        $data = $this->form->getState();


        $data['status'] = $state;
        $data['employee_id'] = Auth::user()?->employee?->id;
        $this->record = static::getModel()::create($data);


        $this->redirect($this->getRedirectUrl());
    }
}
