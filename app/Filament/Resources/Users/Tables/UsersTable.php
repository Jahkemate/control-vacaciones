<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre de Usuario')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->separator(',')
                    ->color(fn($state): string => match ($state) {
                        'super_admin' => 'primary',
                        'employee' => 'info',
                        'admin'    => 'danger',
                        'manager'  => 'secondary',
                        default  =>   'gray'
                    })
                    ->icon(fn($state): Heroicon => match ($state) {
                        'employee' => Heroicon::OutlinedUserCircle,
                        'admin' => Heroicon::OutlinedShieldCheck,
                        'manager' => Heroicon::OutlinedBriefcase,
                        default  =>   Heroicon::OutlinedUser
                    })
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Archivar')
                    ->icon(Heroicon::ArchiveBox),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                DeleteBulkAction::make(),
                ]),
            ]);
    }
}
