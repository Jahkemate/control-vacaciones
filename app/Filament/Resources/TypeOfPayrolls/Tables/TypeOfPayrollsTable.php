<?php

namespace App\Filament\Resources\TypeOfPayrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TypeOfPayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
           ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('payroll_type')
                    ->label('Tipo de Nomina')
                    ->searchable(),
                TextColumn::make('vacations_days')
                    ->label('Vacaciones')
                    ->searchable(),
                TextColumn::make('vacations_bonus')
                    ->label('Bonus')
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
