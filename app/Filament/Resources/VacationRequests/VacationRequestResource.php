<?php

namespace App\Filament\Resources\VacationRequests;

use App\Filament\Resources\VacationRequests\Pages\CreateVacationRequest;
use App\Filament\Resources\VacationRequests\Pages\EditVacationRequest;
use App\Filament\Resources\VacationRequests\Pages\ListVacationRequests;
use App\Filament\Resources\VacationRequests\Schemas\VacationRequestForm;
use App\Filament\Resources\VacationRequests\Tables\VacationRequestsTable;
use App\Models\VacationRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class VacationRequestResource extends Resource
{
    protected static ?string $model = VacationRequest::class;
    protected static ?string $navigationLabel = 'Solicitudes de Vacaciones';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // CONFIGURACIONES DEL BADGE
    //para mostrar el numero de solicitudes
   /*  public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        // Obtener el empleado relacionado al usuario
        $employee = $user->employee;
        $manager = $user->employee;

        if (! $employee) {
            return null;
        } elseif ($user->role === 'manager') {
            return VacationRequest::whereHas('employee', function ($query) use ($manager) {
                $query->where('employee_id', $manager->first()->id);
            })->count();
        } elseif ($user->role === 'admin') {
            return VacationRequest::count();
        } else {
            return VacationRequest::where('employee_id', $employee->first()->id)->count();
        }
    } */

    //para cambiar el color del numero
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    //para mostra un mensaje al pasar por el numero de empleados
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Total de Solicitudes';
    }
    //CIERRE CONFIGURACIONES DEL BADGE

    protected static ?string $recordTitleAttribute = 'VacationRequest';

    public static function form(Schema $schema): Schema
    {
        return VacationRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VacationRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVacationRequests::route('/'),
            'create' => CreateVacationRequest::route('/create'),
            'edit' => EditVacationRequest::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
