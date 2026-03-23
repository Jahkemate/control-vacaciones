<?php

namespace App\Filament\Resources\TypeOfPayrolls\Pages;

use App\Filament\Resources\TypeOfPayrolls\TypeOfPayrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTypeOfPayroll extends CreateRecord
{
    protected static string $resource = TypeOfPayrollResource::class;

     protected function getRedirectUrl(): string
        {
            // Redirige a la página de lista de la tabla
            return $this->getResource()::getUrl('index');
        }
}
