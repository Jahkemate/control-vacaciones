<?php

namespace App\Filament\Resources\PaidRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaidRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('total_days')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                DatePicker::make('request_date')
                    ->required(),
            ]);
    }
}
