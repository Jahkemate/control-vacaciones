<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
     protected function getRedirectUrl(): string
        {
            // Redirige a la página de lista de la tabla
            return $this->getResource()::getUrl('index');
        }
}
