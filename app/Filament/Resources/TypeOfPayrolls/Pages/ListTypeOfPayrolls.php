<?php

namespace App\Filament\Resources\TypeOfPayrolls\Pages;

use App\Filament\Resources\TypeOfPayrolls\TypeOfPayrollResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTypeOfPayrolls extends ListRecords
{
    protected static string $resource = TypeOfPayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
