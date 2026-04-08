<?php

namespace App\Filament\Resources\PaidRequests\Pages;

use App\Filament\Resources\PaidRequests\PaidRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPaidRequest extends EditRecord
{
    protected static string $resource = PaidRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
