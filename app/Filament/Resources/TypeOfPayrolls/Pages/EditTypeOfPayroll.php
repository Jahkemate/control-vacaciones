<?php

namespace App\Filament\Resources\TypeOfPayrolls\Pages;

use App\Filament\Resources\TypeOfPayrolls\TypeOfPayrollResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTypeOfPayroll extends EditRecord
{
    protected static string $resource = TypeOfPayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
