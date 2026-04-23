<?php

namespace App\Filament\Resources\Employees;

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationLabel = 'Empleados';

    protected static ?string $pluralModelLabel = 'Empleados';
    protected static ?string $modelLabel = 'Nuevo Empleado';


    protected static ?int $navigationSort = 6;

    protected static string|UnitEnum|null $navigationGroup = 'Gestion del Sistema';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    // CONFIGURACIONES DEL BADGE
    //para mostrar el numero de empleados
    public static function getNavigationBadge(): ?string
    {

        return Employee::count();
    }

    //para cambiar el color del numero
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    //para mostra un mensaje al pasar por el numero de empleados
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Total de empleados';
    }
    //CIERRE CONFIGURACIONES DEL BADGE
    protected static ?string $recordTitleAttribute = 'Employee';

    public static function form(Schema $schema): Schema
    {
        return EmployeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
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
