<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

     protected function getRedirectUrl(): string
        {
            // Redirige a la página de lista de la tabla
            return $this->getResource()::getUrl('index');
        }
}
