<?php

namespace App\Filament\Resources\BalanceVacations\Pages;

use App\Filament\Resources\BalanceVacations\BalanceVacationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;

class CreateBalanceVacation extends CreateRecord
{
    protected static string $resource = BalanceVacationResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirige a la página de lista de la tabla
        return $this->getResource()::getUrl('index');
    }
}
