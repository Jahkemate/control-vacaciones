<?php

namespace App\Filament\Resources\BalanceVacations\Pages;

use App\Filament\Resources\BalanceVacations\BalanceVacationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBalanceVacations extends ListRecords
{
    protected static string $resource = BalanceVacationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->color('success'),
        ];
    }
}
