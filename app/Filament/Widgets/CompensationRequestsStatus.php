<?php

namespace App\Filament\Widgets;

use App\Models\RequestForCompensation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CompensationRequestsStatus extends StatsOverviewWidget
{
    protected ?string $heading = 'Estadísticas de Solicitud de Compensacion ';

    private function baseQuery()
    {
        return RequestForCompensation::visibleFor(Auth::user());
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Pendientes', $this->getPendingCount())
                ->description('Solicitudes esperando aprobación')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Aprobadas', $this->getApprovedCount())
                ->description('Solicitudes aprobadas')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Rechazadas', $this->getRejectedCount())
                ->description('Solicitudes rechazadas')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),

            Stat::make('Total', $this->getTotalCount())
                ->description('Todas las solicitudes')
                ->color('primary')
                ->icon('heroicon-o-document-text'),
        ];
    }

    private function getPendingCount(): int
    {
        return $this->baseQuery()->where('status', 'pending')->count();
    }

    private function getApprovedCount(): int
    {
        return $this->baseQuery()->where('status', 'approved')->count();
    }

    private function getRejectedCount(): int
    {
        return $this->baseQuery()->where('status', 'rejected')->count();
    }

    private function getTotalCount(): int
    {
        return $this->baseQuery()->count();
    }
}
