<?php

namespace App\Filament\Resources\RequestForCompensation;

use App\Filament\Resources\RequestForCompensation\Pages\CreateRequestForCompensation;
use App\Filament\Resources\RequestForCompensation\Pages\EditRequestForCompensation;
use App\Filament\Resources\RequestForCompensation\Pages\ListRequestForCompensation;
use App\Filament\Resources\RequestForCompensation\Schemas\RequestForCompensationForm;
use App\Filament\Resources\RequestForCompensation\Tables\RequestForCompensationTable;
use App\Models\RequestForCompensation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestForCompensationResource extends Resource
{
    protected static ?string $model = RequestForCompensation::class;
    protected static ?string $navigationLabel = 'Solicitudes de Compensacion';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'requesforcompensation';

    public static function form(Schema $schema): Schema
    {
        return RequestForCompensationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestForCompensationTable::configure($table);
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
            'index' => ListRequestForCompensation::route('/'),
            'create' => CreateRequestForCompensation::route('/create'),
            'edit' => EditRequestForCompensation::route('/{record}/edit'),
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
