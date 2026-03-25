<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditVacationRequest extends EditRecord
{
    protected static string $resource = VacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),

            Action::make('approved')
                ->label('Aprobar Solicitud')
                ->color('secondary')
                ->visible(fn() => Auth::user()?->role, ['admin', 'manager'])
                ->action(fn() => $this->saveAs('approved')),

            Action::make('rejected')
                ->label('Rechazar Solicitud')
                ->color('danger')
                ->visible(fn() => Auth::user()?->role, ['admin', 'manager'])
                ->action(fn() => $this->saveAs('rejected')),
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

    protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    }
}
