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

    protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    }


    //---------Botones en el formulario en el create------------
    protected function getHeaderActions(): array
    {
        return [
            Action::make('draft')
                ->label('Guardar como borrador')
                ->requiresConfirmation()
                ->modalDescription('¿ Desea guardar como Borrador ?')
                ->modalSubmitActionLabel('Si, Guardar')
                ->color('gray')
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
            //--------------------Boton de Imprimir Solicitud----------------------------------------
            Action::make('print')
                ->label('Imprimir Solicitud')
                ->icon(Heroicon::OutlinedPrinter)
                ->color('primary')
                ->visible(fn() => in_array(Auth::user()?->role, ['admin', 'manager', 'employee']))
                ->action(function () {

                    $data = $this->form->getState();

                    $employee = Auth::user()->employee->first();

                    if (!$employee) {
                        Notification::make()
                            ->title('No se encontró el empleado')
                            ->danger()
                            ->send();
                        return;
                    }
                // Agregar información adicional del empleado al array de datos
                    $data['employee_id'] = $employee->first_name . ' ' . $employee->last_name;
                    $data['role.name'] = $employee->user->role;
                    $data['address_number'] = $employee->address_number;
                    $data['department'] = $employee->department?->name;
                    $data['hiring_date'] = Carbon::parse($data['hiring_date'] ?? $employee->hiring_date) // Si no se proporciona una fecha de contratación en el formulario, se utiliza la fecha de contratación del empleado
                        ->locale('es')
                        ->isoFormat('D [de] MMMM [de] YYYY');
                    $data['start_date'] = Carbon::parse($data['start_date'])
                        ->locale('es')
                        ->isoFormat('dddd D [de] MMMM [de] YYYY');

                    $data['end_date'] = Carbon::parse($data['end_date'])
                        ->locale('es')
                        ->isoFormat('dddd D [de] MMMM [de] YYYY');

                    $this->js("
                    fetch('" . route('print.vacation') . "', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '" . csrf_token() . "'
                        },
                        body: JSON.stringify(" . json_encode($data) . ")
                    })
                    .then(res => res.text())
                    .then(html => {
                        let w = window.open('', '_blank');
                        w.document.write(html);
                        w.document.close();
                    });
                ");
                }),
        ];
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
