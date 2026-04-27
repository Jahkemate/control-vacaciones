<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use App\States\EmployeeStatus;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->color('success'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
            ->label('Todos')
            ->icon(Heroicon::UserGroup),
            'active' => Tab::make()
                ->label('Activos')
                ->icon(Heroicon::CheckCircle)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('employee_status',EmployeeStatus::Active)),
            'inactive' => Tab::make()
                ->label('Inactivos')
                ->icon(Heroicon::XCircle)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('employee_status',EmployeeStatus::Inactive)),
            'vacations' => Tab::make()
                ->label('En Vacaciones')
                ->icon(Heroicon::Sun)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('employee_status',EmployeeStatus::Vacations)),
        ];
    }
}
