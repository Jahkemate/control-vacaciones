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
        return [
            'all' => Tab::make()
                ->label('Todas')
                ->icon(Heroicon::ListBullet)
                ->badge(fn() => PaidRequest::visibleFor(Auth::user())->count())
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    PaidRequest::visibleFor(Auth::user())
                ),


            'draft' => Tab::make()
                ->label('Borrador')
                ->icon(Heroicon::PencilSquare)
                ->badge(
                    fn() => PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Draft)
                        ->count()
                )
                ->badgeColor('gray')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Draft)
                ),


            'pending' => Tab::make()
                ->label('Pendientes')
                ->icon(Heroicon::Clock)
                ->badge(
                    fn() => PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Pending)
                        ->count()
                )
                ->badgeColor('primary')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Pending)
                ),


            'approved' => Tab::make()
                ->label('Aprobadas')
                ->icon(Heroicon::CheckCircle)
                ->badge(
                    fn() => PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Approved)
                        ->count()
                )
                ->badgeColor('success')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Approved)
                ),

            'approved_by_manager' => Tab::make()
                ->label('Aprobadas por Jefe')
                ->icon(Heroicon::CheckCircle)
                ->badge(
                    fn() => PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::ApprovedByManager)
                        ->count()
                )
                ->badgeColor('send')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::ApprovedByManager)
                ),


            'rejected' => Tab::make()
                ->label('Rechazadas')
                ->icon(Heroicon::XCircle)
                ->badge(
                    fn() => PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Rejected)
                        ->count()
                )
                ->badgeColor('danger')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    PaidRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Rejected)
                ),


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
