<?php

namespace App\Filament\Resources\RequestForCompensation\Pages;

use App\Filament\Resources\RequestForCompensation\RequestForCompensationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRequestForCompensation extends ListRecords
{
    protected static string $resource = RequestForCompensationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
