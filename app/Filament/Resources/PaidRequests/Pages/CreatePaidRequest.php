<?php

namespace App\Filament\Resources\PaidRequests\Pages;

use App\Filament\Resources\PaidRequests\PaidRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaidRequest extends CreateRecord
{
    protected static string $resource = PaidRequestResource::class;
}
