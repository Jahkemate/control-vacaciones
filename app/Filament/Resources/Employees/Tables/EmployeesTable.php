<?php

namespace App\Filament\Resources\Employees\Tables;

use App\States\EmployeeStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->searchable(),
                TextColumn::make('identity_number')
                    ->searchable(),
                TextColumn::make('address_number')
                    ->searchable(),
                TextColumn::make('hiring_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('anniversary_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employee_state')
                    ->badge()
                    //Convierte los strings dinamicamente a Enum, para que filament pueda leer los metodos del Enum (getLabel, getColor, getIcon), hace esto porque el enum se hace directamente desde la logica des sistema.
                    ->formatStateUsing(fn ($state) => EmployeeStatus::tryFrom($state)?->getLabel())
                    ->color(fn ($state) => EmployeeStatus::tryFrom($state)?->getColor()) // tryFrom es un método de los Enums con valor respaldado (BackedEnum) en PHP, que sirve para convertir un string o número en un Enum de manera segura.
                    ->icon(fn ($state) => EmployeeStatus::tryFrom($state)?->getIcon())
                    ->searchable(),
                TextColumn::make('payroll_id')
                    ->numeric()
                    ->sortable(), 
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
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
