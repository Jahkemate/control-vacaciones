<?php

namespace App\Filament\Resources\VacationRequests\Tables;

use App\States\RequestStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VacationRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->label('Empleado Solicitante'),
                TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->date(),
                TextColumn::make('end_date')
                    ->label('Fecha de Inicio')
                    ->date(),
                TextColumn::make('total_business_days')
                    ->label('Dias Totales'),
                TextColumn::make('state')
                    ->badge()
                    ->label('Estado de la Solicitud')
                    ->formatStateUsing(fn($state) => RequestStatus::tryFrom($state)?->getLabel())
                    ->default(RequestStatus::Draft)
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'success',
                        'pending' => 'primary',
                        'rejected' => 'danger',
                        'published' => 'info'
                    })
                    ->icon(fn(string $state): Heroicon => match ($state) {
                        'draft' => Heroicon::OutlinedDocumentText,
                        'approved' => Heroicon::OutlinedCheckCircle,
                        'pending' => Heroicon::OutlinedClock,
                        'rejected' => Heroicon::OutlinedXCircle,
                        'published' => Heroicon::OutlinedPaperAirplane
                    }),
                TextColumn::make('created_at')
                    ->label('Fecha de Solicitud')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comment')
                    ->limit(10)
                    ->tooltip(fn($record) => $record->comment) //muestra el texto complteo al pasar el mouse
                    ->label('Comentario'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('viewComments')
                    ->color(fn($record) =>
                    $record->comment ? 'success' : 'gray') // hace el color dinamico
                    ->label('Ver Comentario')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Comentarios')
                    ->modalWidth('sm') // cambia el tamaño del modal
                    ->modalContent(fn($record) => view('filament.modals.comments', [
                        'comments' => $record->comment
                    ]))
                    ->modalSubmitAction(false),
                EditAction::make(),
                DeleteAction::make(),
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
