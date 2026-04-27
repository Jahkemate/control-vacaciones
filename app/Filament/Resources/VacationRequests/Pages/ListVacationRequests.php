<?php

namespace App\Filament\Resources\VacationRequests\Pages;

use App\Filament\Resources\VacationRequests\VacationRequestResource;
use App\Models\Employee;
use App\Models\VacationRequest;
use App\States\RequestStatus;
use BladeUI\Icons\Components\Icon;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListVacationRequests extends ListRecords
{
    protected static string $resource = VacationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->color('success')
                ->icon(Heroicon::OutlinedDocumentDuplicate),
        ];
    }

    //----------------------Filtro en la part de arriba de la tabla--------------------------------
    public function getTabs(): array
    {
        // Esto para traer solo los borradores del usuario logeado
        $employeeId = Auth::user()->employee?->id;
        return [
            'all' => Tab::make()
                ->label('Todas')
                ->icon(Heroicon::ListBullet)
                ->badge(fn() => VacationRequest::visibleFor(Auth::user())->count()),

            'draft' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Draft)->where('employee_id', $employeeId))
                ->label('Borrador')
                ->icon(Heroicon::PencilSquare)
                ->badge(fn() =>VacationRequest::where('employee_id', $employeeId)->where('status', RequestStatus::Draft)->count())
                ->badgeColor('gray'),

            'pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Pending))
                ->label('Pendientes')
                ->icon(Heroicon::Clock)
                ->badge(fn() => VacationRequest::visibleFor(Auth::user())->where('status', RequestStatus::Pending)->count())
                ->badgeColor('primary'),

            'approved' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Approved))
                ->label('Aprobadas')
                ->icon(Heroicon::CheckCircle)
                ->badge(fn() => VacationRequest::visibleFor(Auth::user())->where('status', RequestStatus::Approved)->count())
                ->badgeColor('success'),

            'rejected' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', RequestStatus::Rejected))
                ->label('Rechazadas')
                ->icon(Heroicon::XCircle)
                ->badge(fn() => VacationRequest::visibleFor(Auth::user())->where('status', RequestStatus::Rejected)->count())
                ->badgeColor('danger'),
        ];
    }

    //----------------------- Se trae la logica del modelo ----------------------------------
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->visibleFor(Auth::user());
    }
    //--------------------------------------------------------------------------------------------------------
}
