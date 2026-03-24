<?php

namespace App\Filament\Resources\VacationRequests\Tables;

use App\States\RequestStatus;
use Filament\Actions\BulkActionGroup;
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
                        'rejected' => 'danger'
                    })
                    ->icon(fn(string $state): Heroicon => match ($state) {
                        'draft' => Heroicon::OutlinedDocumentText,
                        'approved' => Heroicon::OutlinedCheckCircle,
                        'pending' => Heroicon::OutlinedClock,
                        'rejected' => Heroicon::OutlinedXCircle
                    }),
                TextColumn::make('created_at')
                    ->label('Fecha de Solicitud')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comment')
                    ->label('Comentario'),
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
