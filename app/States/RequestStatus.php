<?php

namespace App\States;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum RequestStatus: string implements HasLabel, HasColor
{
    case Approved = 'approved';
    case Draft = 'draft';
    case Pending = 'pending';
    case Rejected = 'rejected';
    case ApprovedByManager = 'approved_by_manager';
    case ApprovedByRRHH = 'approved_by_rrhh';


    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Approved => 'Aprobadas',
            self::Draft => 'Borrador',
            self::Pending => 'Pendiente',
            self::Rejected => 'Rechazadas',
            self::ApprovedByManager => 'Aprobadas por el Jefe',
            self::ApprovedByRRHH => 'Aprobadas por RRHH',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Approved => 'success',
            self::Pending => 'primary',
            self::Rejected => 'danger',
            self::ApprovedByManager => 'info',
            self::ApprovedByRRHH => 'info'
        };
    }

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::Draft => Heroicon::OutlinedDocumentText,
            self::Approved => Heroicon::OutlinedCheckCircle,
            self::Pending => Heroicon::OutlinedClock,
            self::Rejected => Heroicon::OutlinedXCircle,
            self::ApprovedByManager => Heroicon::OutlinedPaperAirplane,
            self::ApprovedByRRHH => Heroicon::OutlinedPaperAirplane
        };
    }
}
