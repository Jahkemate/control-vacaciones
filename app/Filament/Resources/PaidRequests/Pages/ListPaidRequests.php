<?php

namespace App\Filament\Resources\PaidRequests\Pages;

use App\Filament\Resources\PaidRequests\PaidRequestResource;
use App\Models\PaidRequest;
use App\States\RequestStatus;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

     //----------------------Filtri en la part de arriba de la tabla--------------------------------
    public function getTabs(): array
    {
        
       $employeeId = Auth::user()->employee?->id;
        return [
            'all' => Tab::make()
                ->label('Todas')
                ->icon(Heroicon::ListBullet)
                ->badge(fn () =>  PaidRequest::visibleFor(Auth::user())->count()),
            'draft' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Draft)->where('employee_id', $employeeId))
                ->label('Borrador')
                ->icon(Heroicon::PencilSquare)
                ->badge(fn() => PaidRequest::where('status', RequestStatus::Draft)->where('employee_id', $employeeId)->count())
                ->badgeColor('gray'),
            'pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status' ,RequestStatus::Pending))
                ->label('Pendientes')
                ->icon(Heroicon::Clock)
                ->badge(fn() => PaidRequest::visibleFor(Auth::user())->where('status', RequestStatus::Pending)->count())
                ->badgeColor('primary'),
            'approved' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Approved))
                ->label('Aprobadas')
                ->icon(Heroicon::CheckCircle)
                ->badge(fn() => PaidRequest::visibleFor(Auth::user())->where('status', RequestStatus::Approved)->count())
                ->badgeColor('success'),
            'rejected' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Rejected))
                ->label('Rechazadas')
                ->icon(Heroicon::XCircle)
                ->badge(fn() => PaidRequest::visibleFor(Auth::user())->where('status', RequestStatus::Rejected)->count())
                ->badgeColor('danger'),
        ];
    }
    //--------------------------------------------------------------------------------------------------------

    //----------------------- Se trae la logica del modelo ----------------------------------
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->visibleFor(Auth::user());
    }
    //--------------------------------------------------------------------------------------------------------
}


