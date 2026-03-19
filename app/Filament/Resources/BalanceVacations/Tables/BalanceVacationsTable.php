<?php

namespace App\Filament\Resources\BalanceVacations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BalanceVacationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                 TextColumn::make('employee.full_name')
                    ->label('Nombre Empleado')
                    ->searchable(['first_name', 'last_name']), //Esto es para que al momento de buscar no de un error, ya que para mostrar nombre completo se esta usando un accesor
                TextColumn::make('accrued_total')
                    ->label('Total Acumulado')
                    ->searchable(),
                TextColumn::make('accrued_this_year')
                    ->label('Acumulado este Año')
                    ->searchable(),
                TextColumn::make('used')
                    ->label('Vacaciones Usadas')
                    ->searchable(),
                TextColumn::make('balance')
                    ->label('Balance de Vaciones')
                    ->searchable(),
                TextColumn::make('pendientes')
                    ->label('Pendientes de Gozar')
                    ->searchable(),
                TextColumn::make('notas')
                    ->label('Notas')
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
