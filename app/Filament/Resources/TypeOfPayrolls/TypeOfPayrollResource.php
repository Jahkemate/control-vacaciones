<?php

namespace App\Filament\Resources\TypeOfPayrolls;

use App\Filament\Resources\TypeOfPayrolls\Pages\CreateTypeOfPayroll;
use App\Filament\Resources\TypeOfPayrolls\Pages\EditTypeOfPayroll;
use App\Filament\Resources\TypeOfPayrolls\Pages\ListTypeOfPayrolls;
use App\Filament\Resources\TypeOfPayrolls\Schemas\TypeOfPayrollForm;
use App\Filament\Resources\TypeOfPayrolls\Tables\TypeOfPayrollsTable;
use App\Models\Payroll;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TypeOfPayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;
    protected static ?string $navigationLabel = 'Tipo de Nomina';

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Gestion de Vacaciones';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'Payroll';
    

    public static function form(Schema $schema): Schema
    {
        return TypeOfPayrollForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypeOfPayrollsTable::configure($table);
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
            'index' => ListTypeOfPayrolls::route('/'),
            'create' => CreateTypeOfPayroll::route('/create'),
            'edit' => EditTypeOfPayroll::route('/{record}/edit'),
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
