<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVacationRequest extends CreateRecord
{
    protected static string $resource = VacationRequestResource::class;
}
