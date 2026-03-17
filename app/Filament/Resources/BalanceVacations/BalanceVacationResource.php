<?php

namespace App\Filament\Resources\BalanceVacations;

use App\Filament\Resources\BalanceVacations\Pages\CreateBalanceVacation;
use App\Filament\Resources\BalanceVacations\Pages\EditBalanceVacation;
use App\Filament\Resources\BalanceVacations\Pages\ListBalanceVacations;
use App\Filament\Resources\BalanceVacations\Schemas\BalanceVacationForm;
use App\Filament\Resources\BalanceVacations\Tables\BalanceVacationsTable;
use App\Models\BalanceVacation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BalanceVacationResource extends Resource
{
    protected static ?string $model = BalanceVacation::class;

     protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = 'Gestion de Vacaciones';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'balance_vacation';

    public static function form(Schema $schema): Schema
    {
        return BalanceVacationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BalanceVacationsTable::configure($table);
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
            'index' => ListBalanceVacations::route('/'),
            'create' => CreateBalanceVacation::route('/create'),
            'edit' => EditBalanceVacation::route('/{record}/edit'),
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
