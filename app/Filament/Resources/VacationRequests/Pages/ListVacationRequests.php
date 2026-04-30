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
        return [
            'all' => Tab::make()
                ->label('Todas')
                ->icon(Heroicon::ListBullet)
                ->badge(fn() => VacationRequest::visibleFor(Auth::user())->count())
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    VacationRequest::visibleFor(Auth::user())
                ),


            'draft' => Tab::make()
                ->label('Borrador')
                ->icon(Heroicon::PencilSquare)
                ->badge(
                    fn() => VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Draft)
                        ->count()
                )
                ->badgeColor('gray')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Draft)
                ),


            'pending' => Tab::make()
                ->label('Pendientes')
                ->icon(Heroicon::Clock)
                ->badge(
                    fn() => VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Pending)
                        ->count()
                )
                ->badgeColor('primary')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Pending)
                ),


            'approved' => Tab::make()
                ->label('Aprobadas')
                ->icon(Heroicon::CheckCircle)
                ->badge(
                    fn() => VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Approved)
                        ->count()
                )
                ->badgeColor('success')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Approved)
                ),

            'approved_by_manager' => Tab::make()
                ->label('Aprobadas por Jefe')
                ->icon(Heroicon::CheckCircle)
                ->badge(
                    fn() => VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::ApprovedByManager)
                        ->count()
                )
                ->badgeColor('send')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::ApprovedByManager)
                ),


            'rejected' => Tab::make()
                ->label('Rechazadas')
                ->icon(Heroicon::XCircle)
                ->badge(
                    fn() => VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Rejected)
                        ->count()
                )
                ->badgeColor('danger')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    VacationRequest::visibleFor(Auth::user())
                        ->where('status', RequestStatus::Rejected)
                ),


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
