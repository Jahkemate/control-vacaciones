<?php

namespace App\Filament\Resources\BalanceVacations\Schemas;

use App\Models\Employee;
use App\Models\Payroll;
use App\States\EmployeeStatus;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BalanceVacationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        // Este formulario solo es visual, solo es para extraer los datos que haran el balance.
            ->components([  Section::make('Personal Info')
                ->columns(1)
                ->schema([
                    Select::make('employee_id')
                        ->relationship('employee', 'first_name')
                        ->getOptionLabelFromRecordUsing(fn ($record) => 
                                $record->first_name . ' ' . $record->last_name
                            )
                        ->label('Nombre Empleado')
                        ->reactive()
                        //Este metodo es para traer los datos de la tabla de employee al formulario de balance_vacation
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                    $employee = Employee::find($state);

                                    if ($employee) {
                                        $set('identity_number', $employee->identity_number);
                                        $set('address_number', $employee->address_number);
                                        $set('hiring_date', $employee->hiring_date);
                                        $set('anniversary_date', $employee->anniversary_date);
                                        $set('employee_state', $employee->employee_state);
                                        $set('department_id', $employee->department_id);
                                        $set('payroll_id', $employee->payroll_id);
                                        $set('user_id', $employee->user_id);
                                    }

                                     // Calcular vacaciones
                                        $total = self::calculateAccruedTotal($state);
                                        $thisYear = self::calculateAccruedThisYear($employee);
                                        //$pendientes = self::calculatePendientes($total, $get('used') ?? 0);

                                        $set('accrued_total', $total);
                                        $set('accrued_this_year', $thisYear);
                                        //$set('pendings',$pendientes);

                                    // Reiniciar usadas y recalcular balance
                                        $set('used', 0);
                                        $set('balance', $total);
                                  
                                })

                        ->required(),
                    TextInput::make('identity_number')
                        ->label('Numero de Identidad')
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('address_number')
                        ->label('Numero de Direccion')
                        ->disabled()
                        ->dehydrated(false),
                    DatePicker::make('hiring_date')
                        ->label('Fecha de Contratacion')
                        ->disabled()
                        ->dehydrated(false),
                    DatePicker::make('anniversary_date')
                        ->label('Fecha de Aniversario')
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('department_id')
                            ->relationship('department','name')
                            ->label('Departamento')
                            ->disabled()
                            ->dehydrated(false),
                    Select::make('employee_state')
                        ->label('Estado de Empleado')
                        ->options(EmployeeStatus::class)
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('payroll_id')
                        ->label('Tipo de Nomina')
                        ->relationship('payroll', 'payroll_type')
                        ->disabled()
                        ->dehydrated(false),
                    Select::make('user_id')
                            ->relationship('user','name')
                            ->label('Usuario')
                            ->disabled()
                            ->dehydrated(false),
                ]),

                Section::make('Balance Info')
                ->columns(1)
                ->schema([
                    //Total acmumulado
                    TextInput::make('accrued_total')
                    ->readOnly()
                    ->default(0)
                    ->label('Total Acumulado'),  

                    //Acumulado este año
                    TextInput::make('accrued_this_year')
                    ->readOnly()
                    ->default(0)
                    ->label('Acumulado este Año'),

                    //Vacaciones usadas
                    TextInput::make('used')
                    ->reactive()
                    ->default(0)
                    ->label('Vacaciones Usadas')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                       $total = (int) ($get('accrued_total') ?? 0);
                        $used = (int) $state;

                        // Balance contable (puede ser negativo si excede)
                        $balance = $total - $used;

                        $set('balance', $balance);
                    }),

                    //Balance
                    TextInput::make('balance')
                    ->default(0)
                    ->readOnly()
                    ->label('Balance'),

                    //Pednientes de Gozar
                    TextInput::make('pendings')
                    ->default(0)
                    ->readOnly()
                    ->label('Pendientes de Gozar'),

                ]), 
            ]);  
    }

    //Metodos para hacer los calculos
    //Metodo que va a hacer el calculo del acumulado total 
    public static function calculateAccruedTotal($employee_id)
        {
                if (!$employee_id) return 0;

            $employee_id = Employee::find($employee_id);
                if (!$employee_id || !$employee_id->payroll_id) return 0;

            $payroll = Payroll::find($employee_id->payroll_id);
                if (!$payroll) return 0;

            $yearsWorked = Carbon::parse($employee_id->hiring_date)->diffInYears(now());

                return (int) floor($yearsWorked * ($payroll->vacations_days ?? 0));
        }

    // Metodo para hacer el claculo de acumulado este año
    // Vacaciones acumuladas este año (proporcional exacta)
    public static function calculateAccruedThisYear(Employee $employee_id)
        
    {
       if (!$employee_id || !$employee_id->hiring_date) return 0;

            $hiringDate = Carbon::parse($employee_id->hiring_date);
            $today = Carbon::today();

            // Equivalente a SIFECHA(...;"yd")
            $daysThisYear = $hiringDate->diffInDays($today) % 365 + 1;

            // Fórmula tipo Excel
            $vacationDays = ($daysThisYear / 30) * 1.83;

        return (int) floor($vacationDays);
    }


    //Metodo para hacer el el calculo del balance
   /*  public static function calculatePendientes(int $total, int $used): int
        {
            return $total - $used; // Si quieres permitir negativos
    // return max(0, $total - $used); // Si no quieres negativos

        }  */

}
