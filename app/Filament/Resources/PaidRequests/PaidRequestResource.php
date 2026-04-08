<?php

namespace App\Filament\Resources\PaidRequests;

use App\Filament\Resources\PaidRequests\Pages\CreatePaidRequest;
use App\Filament\Resources\PaidRequests\Pages\EditPaidRequest;
use App\Filament\Resources\PaidRequests\Pages\ListPaidRequests;
use App\Filament\Resources\PaidRequests\Schemas\PaidRequestForm;
use App\Filament\Resources\PaidRequests\Tables\PaidRequestsTable;
use App\Models\PaidRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaidRequestResource extends Resource
{
    protected static ?string $model = PaidRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'paidrequest';

    public static function form(Schema $schema): Schema
    {
        return PaidRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaidRequestsTable::configure($table);
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
            'index' => ListPaidRequests::route('/'),
            'create' => CreatePaidRequest::route('/create'),
            'edit' => EditPaidRequest::route('/{record}/edit'),
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
