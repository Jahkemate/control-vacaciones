<?php

namespace App\Filament\Resources\RequestForCompensation\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RequestForCompensationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('date_creation')
                    ->required(),
                TextInput::make('total_days')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                DatePicker::make('approval_date')
                    ->required(),
                DatePicker::make('pending_date')
                    ->required(),
                Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
