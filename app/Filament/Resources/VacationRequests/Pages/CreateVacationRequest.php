<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateVacationRequest extends CreateRecord
{
    protected static string $resource = VacationRequestResource::class;

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
                ->color('gray')
                ->visible(fn() => Auth::user()?->role, ['employee', 'admin', 'manager'])
                ->action(fn() => $this->saveAs('draft')),

            Action::make('submit')
                ->label('Enviar solicitud')
                ->color('primary')
                ->visible(fn() => Auth::user()?->role, ['employee', 'admin', 'manager'])
                ->action(fn() => $this->saveAs('pending')),

            Action::make('approved')
                ->label('Enviar solicitud')
                ->color('primary')
                ->visible(fn() => Auth::user()?->role, ['employee', 'admin', 'manager'])
                ->action(fn() => $this->saveAs('pending')),
        ];
    }

    protected function saveAs(string $state)
    {
        $this->form->validate();

        $data = $this->form->getState();
        $data['state'] = $state;

        $this->record = static::getModel()::create($data);

        $this->redirect($this->getRedirectUrl());
    }
}
