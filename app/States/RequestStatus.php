<?php

namespace App\States;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum RequestStatus: string implements HasLabel
{
    case Approved = 'approved';
    case Draft = 'draft';
    case Pending = 'pending';
    case Rejected = 'rejected';
    case Published = 'published';


    public function getLabel(): string|Htmlable|null
    {
        return match ($this){
            self::Approved => 'Aprobadas',
            self::Draft => 'Borrador',
            self::Pending => 'Pendiente',
            self::Rejected => 'Rechazadas',
            self::Published => 'Publicado'
        };
    }
}
