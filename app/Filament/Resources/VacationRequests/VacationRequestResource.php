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
use UnitEnum;

class VacationRequestResource extends Resource
{
    protected static ?string $model = VacationRequest::class;
    protected static ?string $navigationLabel = 'Solicitudes de Vacaciones';

    protected static string|UnitEnum|null $navigationGroup = 'Solicitudes';
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;

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
