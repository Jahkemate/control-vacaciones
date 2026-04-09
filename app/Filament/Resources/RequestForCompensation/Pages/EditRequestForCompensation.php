<?php

namespace App\Filament\Resources\RequestForCompensation\Pages;

use App\Filament\Resources\RequestForCompensation\RequestForCompensationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRequestForCompensation extends EditRecord
{
    protected static string $resource = RequestForCompensationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
