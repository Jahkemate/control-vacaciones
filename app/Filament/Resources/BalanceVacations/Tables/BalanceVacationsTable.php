<?php

namespace App\Filament\Resources\BalanceVacations\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BalanceVacationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('employee.full_name')
                    ->alignCenter()
                    ->label('Nombre Empleado')
                    ->searchable(['first_name', 'last_name']), //Esto es para que al momento de buscar no de un error, ya que para mostrar nombre completo se esta usando un accesor
                TextColumn::make('accrued_total')
                    ->alignCenter()
                    ->label('Total Acumulado')
                    ->searchable(),
                TextColumn::make('accrued_this_year')
                    ->alignCenter()
                    ->label('Acumulado este Año')
                    ->searchable(),
                TextColumn::make('used')
                    ->alignCenter()
                    ->label('Vacaciones Usadas')
                    ->searchable(),
                TextColumn::make('balance')
                    ->alignCenter()
                    ->label('Balance de Vaciones')
                    ->searchable(),
                TextColumn::make('pendings')
                    ->alignCenter()
                    ->label('Pendientes de Gozar')
                    ->searchable(),
                TextColumn::make('notes')
                    ->limit(10)
                    ->tooltip(fn($record) => $record->notes) //muestra el texto complteo al pasar el mouse
                    ->alignCenter()
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
            ->modifyQueryUsing(function ($query) {
                return $query
                    ->visibleToUser()
                    ->orderBy('created_at', 'desc');
            })
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('verNotas')
                    ->color(fn($record) =>
                    $record->notes ? 'success' : 'gray') // hace el color dinamico
                    ->label('Ver notas')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Notas')
                    ->modalWidth('sm') // cambia el tamaño del modal
                    ->modalContent(fn($record) => view('filament.modals.notes', [
                        'notes' => $record->notes
                    ]))
                    ->modalSubmitAction(false),

                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make()
                    ->label('Borrar')
                    ->modalHeading('Borrar Balance')
                    ->modalDescription('Estas seguro/a que quieres borrar este balance ')
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
