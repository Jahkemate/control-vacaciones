<?php

namespace App\Filament\Resources\PaidRequests\Pages;

use App\Filament\Resources\PaidRequests\PaidRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPaidRequests extends ListRecords
{
    protected static string $resource = PaidRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->color('success')
            ->icon(Heroicon::OutlinedDocumentDuplicate),
        ];
    }
}
