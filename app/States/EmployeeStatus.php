<?php

namespace App\States;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

// Declaramos un Enum llamado EmployeeStatus, de tipo string, implementando interfaces de Filament, esto se hace directamente desde la logica el sistema, no desde la base de datos
enum EmployeeStatus: string implements HasColor, HasLabel, HasIcon
{
    // Aquí definimos los posibles estados con sus valores "backing values"
    // Los valores son los que se usarían en la lógica interna del sistema. 
    case Activo = 'activo';
    case Inactivo = 'inactivo';
    case Eliminado = 'eliminado';
    

    // getColor(): Devuelve el color que Filament usará en un badge (el badge se pone en la tabla de employees).
    // Puede devolver un string (como 'success') o un array si se quiere algo más complejo
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Activo => 'success',
            self::Inactivo => 'warning',
            self::Eliminado => 'danger',
        };
    }

    // getLabel(): Devuelve el texto visible que aparecerá en la UI
    // Esto permite que el valor interno sea distinto al texto que ve el usuario
    public function getLabel(): string
    {
        return match ($this) {
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
            self::Eliminado => 'Eliminado',
        };
    }

      // getIcon(): Devuelve un ícono de Heroicon que se puede mostrar junto al badge
     public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::Activo => Heroicon::Check,
            self::Inactivo => Heroicon::ExclamationCircle,
            self::Eliminado => Heroicon::XMark,
        };
    }

     // options(): Método helper que devuelve el array que se puede usar en un Select del formulario de Employee
    // Estructura: [valor_interno => label_para_mostrar]
    //Devuelve un array [valor => label] listo para un Select en Filament.
    public static function options(): array
    {
        return collect(self::cases()) // Obtenemos todos los cases del Enum
            ->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]) // Creamos el array
            ->toArray(); // Se convierte la colección a un array simple
    }
}