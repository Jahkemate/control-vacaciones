<?php

namespace App\Filament\Resources\Employees\Tables;

use App\States\EmployeeStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
                TextColumn::make('full_name')
                    ->label('Nombre')
                    ->searchable(['first_name', 'last_name']), //Esto es para que al momento de buscar no de un error, ya que para mostrar nombre completo se esta usando un accesor
                TextColumn::make('identity_number')
                    ->label('Numero de Identidad')
                    ->searchable(),
                TextColumn::make('address_number')
                    ->label('Numero Direccion')
                    ->searchable(),
                TextColumn::make('hiring_date')
                    ->label('Fecha Contratacion')
                    ->date()
                    ->searchable(),
                TextColumn::make('anniversary_date')
                    ->label('Fecha Aniversario')
                    ->date()
                    ->searchable(),
                TextColumn::make('department.name')
                    ->label('Departamento')
                    ->searchable(),
                TextColumn::make('employee_state')
                    ->label('Estado')
                    ->badge()
                    //Convierte los strings dinamicamente a Enum, para que filament pueda leer los metodos del Enum (getLabel, getColor, getIcon), hace esto porque el enum se hace directamente desde la logica des sistema.
                    ->formatStateUsing(fn ($state) => EmployeeStatus::tryFrom($state)?->getLabel())
                    ->color(fn ($state) => EmployeeStatus::tryFrom($state)?->getColor()) // tryFrom es un método de los Enums con valor respaldado (BackedEnum) en PHP, que sirve para convertir un string o número en un Enum de manera segura.
                    ->icon(fn ($state) => EmployeeStatus::tryFrom($state)?->getIcon())
                    ->searchable(),
                TextColumn::make('payroll.payroll_type')
                    ->label('Nomina')
                    ->searchable(), 
                TextColumn::make('user.name')
                    ->label('Usario')
                    ->numeric()
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
               EditAction::make()
                ->label('Editar'),
                DeleteAction::make()
                ->label('Borrar')
                ->modalHeading('Borrar Empleado')
                ->modalDescription('Estas seguro/a que quieres borrar este Empleado')
                ->modalSubmitActionLabel('Si, borralo'),
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
